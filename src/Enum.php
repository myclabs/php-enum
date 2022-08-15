<?php
/**
 * @link    http://github.com/myclabs/php-enum
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

namespace MyCLabs\Enum;

/**
 * Base Enum class
 *
 * Create an enum by implementing this class and adding class constants.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 * @author Daniel Costa <danielcosta@gmail.com>
 * @author Mirosław Filip <mirfilip@gmail.com>
 *
 * @psalm-template T
 * @psalm-immutable
 * @psalm-consistent-constructor
 */
abstract class Enum implements \JsonSerializable, \Stringable
{
    /**
     * Enum value
     *
     * @var mixed
     * @psalm-var T
     */
    protected $value;

    /**
     * Enum key, the constant name
     *
     * @var string
     */
    protected $key;

    /**
     * Store existing constants in a static cache per object.
     *
     *
     * @var array<class-string, array<string, static>>
     * @psalm-var array<class-string, array<string, mixed>>
     */
    protected static $cache = [];

    /**
     * Cache of instances of the Enum class
     *
     * @var array<class-string, array<string, static>>
     * @psalm-var array<class-string, array<string, static>>
     */
    protected static $instances = [];

    /**
     * Creates a new value of some type
     *
     * @psalm-pure
     * @param mixed $value
     *
     * @psalm-param T $value
     * @throws \UnexpectedValueException if incompatible type is given.
     */
    public function __construct($value)
    {
        if ($value instanceof static) {
            /** @psalm-var T $value */
            $value = $value->getValue();
        }

        /** @psalm-suppress ImplicitToStringCast assertValidValueReturningKey returns always a string but psalm has currently an issue here */
        $this->key = static::assertValidValueReturningKey($value);

        /** @psalm-var T $value */
        $this->value = $value;
    }

    /**
     * This method exists only for the compatibility reason when deserializing a previously serialized version
     * that didn't have the key property
     *
     * @param array<string,mixed> $data
     */
    public function __unserialize(array $data): void
    {
        $zeroChar = \chr(0);
        foreach ($data as $key => $value) {
            if (false !== strpos($key, $zeroChar)) {
                $parts = explode($zeroChar, $key);
                $key = $parts[array_key_last($parts)];
            }
            $this->{$key} = $value;
        }

        $this->__wakeup();
    }

    /**
     * This method exists only for the compatibility reason when deserializing a previously serialized version
     * that didn't have the key property
     */
    public function __wakeup()
    {
        /** @psalm-suppress DocblockTypeContradiction key can be null when deserializing an enum without the key */
        if ($this->key === null) {
            /**
             * @psalm-suppress InaccessibleProperty key is not readonly as marked by psalm
             * @psalm-suppress PossiblyFalsePropertyAssignmentValue deserializing a case that was removed
             */
            $this->key = static::assertValidValueReturningKey($this->value);
        }
    }

    /**
     * @param mixed $value
     *
     * @return static
     */
    public static function from($value): self
    {
        $key = static::assertValidValueReturningKey($value);

        return static::__callStatic($key, []);
    }

    /**
     * @psalm-pure
     * @return mixed
     * @psalm-return T
     */
    #[\ReturnTypeWillChange]
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns the enum key (i.e. the constant name).
     *
     * @psalm-pure
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @psalm-pure
     * @psalm-suppress InvalidCast
     */
    public function __toString(): string
    {
        return (string) $this->value;
    }

    /**
     * Determines if Enum should be considered equal with the variable passed as a parameter.
     * Returns false if an argument is an object of different class or not an object.
     *
     * This method is final, for more information read https://github.com/myclabs/php-enum/issues/4
     *
     * @psalm-pure
     * @psalm-param mixed $variable
     */
    final public function equals($variable = null): bool
    {
        return $variable instanceof self
            && $this->getValue() === $variable->getValue()
            && static::class === \get_class($variable);
    }

    /**
     * Returns the names (keys) of all constants in the Enum class
     *
     * @psalm-pure
     * @psalm-return list<string>
     * @return array<string>
     */
    public static function keys(): array
    {
        return \array_keys(static::toArray());
    }

    /**
     * Returns instances of the Enum class of all Enum constants
     *
     * @psalm-pure
     * @psalm-return array<string, static>
     * @return static[] Constant name in key, Enum instance in value
     */
    public static function values(): array
    {
        $values = [];

        /** @psalm-var T $value */
        foreach (static::toArray() as $key => $value) {
            $values[$key] = new static($value);
        }

        return $values;
    }

    /**
     * Returns all possible values as an array
     *
     * @psalm-pure
     * @psalm-suppress ImpureStaticProperty
     *
     * @psalm-return array<string, mixed>
     * @return array<string, mixed> Constant name in key, constant value in value
     */
    public static function toArray(): array
    {
        $class = static::class;

        if (!isset(static::$cache[$class])) {
            /** @psalm-suppress ImpureMethodCall this reflection API usage has no side-effects here */
            $reflection            = new \ReflectionClass($class);
            /** @psalm-suppress ImpureMethodCall this reflection API usage has no side-effects here */
            static::$cache[$class] = $reflection->getConstants();
        }

        return static::$cache[$class];
    }

    /**
     * Check if is valid enum value
     *
     * @param mixed $value
     *
     * @psalm-param mixed $value
     * @psalm-pure
     * @psalm-assert-if-true T $value
     */
    public static function isValid($value): bool
    {
        return \in_array($value, static::toArray(), true);
    }

    /**
     * Asserts valid enum value
     *
     * @psalm-pure
     * @psalm-assert T $value
     *
     * @param mixed $value
     */
    public static function assertValidValue($value): void
    {
        static::assertValidValueReturningKey($value);
    }

    /**
     * Asserts valid enum value
     *
     * @psalm-pure
     * @psalm-assert T $value
     *
     * @param mixed $value
     */
    protected static function assertValidValueReturningKey($value): string
    {
        if (null === ($key = static::search($value))) {
            throw new \UnexpectedValueException("Value '$value' is not part of the enum " . static::class);
        }

        return $key;
    }

    /**
     * Check if is valid enum key
     *
     * @psalm-param string $key
     * @psalm-pure
     */
    public static function isValidKey(string $key): bool
    {
        $array = static::toArray();

        return isset($array[$key]) || \array_key_exists($key, $array);
    }

    /**
     * Return key for value
     *
     * @param mixed $value
     *
     * @psalm-param mixed $value
     * @psalm-pure
     */
    public static function search($value): ?string
    {
        $index = \array_search($value, static::toArray(), true);

        return false === $index ? null : $index;
    }

    /**
     * Returns a value when called statically like so: MyEnum::SOME_VALUE() given SOME_VALUE is a class constant
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return static
     * @throws \BadMethodCallException
     *
     * @psalm-pure
     */
    public static function __callStatic($name, $arguments)
    {
        $class = static::class;
        if (!isset(static::$instances[$class][$name])) {
            $array = static::toArray();
            if (!isset($array[$name]) && !\array_key_exists($name, $array)) {
                $message = "No static method or enum constant '$name' in class " . static::class;
                throw new \BadMethodCallException($message);
            }

            return static::$instances[$class][$name] = new static($array[$name]);
        }

        return clone static::$instances[$class][$name];
    }

    /**
     * Specify data which should be serialized to JSON. This method returns data that can be serialized by json_encode()
     * natively.
     *
     * @return mixed
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @psalm-pure
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->getValue();
    }
}
