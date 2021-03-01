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
 * @author Alexandru Pătrănescu <drealecs@gmail.com>
 *
 * @psalm-template T
 * @psalm-immutable
 * @psalm-consistent-constructor
 */
abstract class Enum implements \JsonSerializable
{
    /**
     * Enum value
     *
     * @var mixed
     * @psalm-var T
     */
    private $value;

    /**
     * Enum key, the constant name
     *
     * @var string
     */
    private $key;

    /**
     * Store existing constants in a static cache per object.
     *
     *
     * @var array
     * @psalm-var array<class-string, array<string, mixed>>
     */
    private static $cache = [];

    /**
     * Cache of instances of the Enum class
     *
     * @var array
     * @psalm-var array<class-string, array<string, static>>
     */
    private static $instances = [];

    /**
     * Creates a new value of some type
     *
     * @psalm-pure
     * @param string $key
     * @param mixed $value
     *
     * @psalm-param T $value
     */
    final private function __construct(string $key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * The single place where the instance is created, other than unserialize
     *
     * @psalm-pure
     * @param string $key
     * @param mixed $value
     * @return static
     */
    private static function getInstance(string $key, $value): self
    {
        if (!isset(self::$instances[static::class][$key])) {
            return self::$instances[static::class][$key] = new static($key, $value);
        }

        return clone self::$instances[static::class][$key];
    }

    /**
     * @param mixed $value
     * @return static
     * @psalm-return static<T>
     */
    final public static function from($value): self
    {
        $key = self::search($value);

        if ($key === false) {
            throw new \UnexpectedValueException("Value '{$value}' is not part of the enum " . static::class);
        }

        return self::getInstance($key, $value);
    }

    /**
     * @param mixed $value
     * @return static|null
     * @psalm-return static<T>|null
     */
    final public static function tryFrom($value): ?self
    {
        $key = self::search($value);

        if ($key === false) {
            return null;
        }

        return self::getInstance($key, $value);
    }

    /**
     * @psalm-pure
     * @return mixed
     * @psalm-return T
     */
    final public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns the enum key (i.e. the constant name).
     *
     * @psalm-pure
     * @return string
     */
    final public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @psalm-pure
     * @psalm-suppress InvalidCast
     * @return string
     */
    final public function __toString(): string
    {
        return (string)$this->value;
    }

    /**
     * Determines if Enum should be considered equal with the variable passed as a parameter.
     * Returns false if an argument is an object of different class or not an object.
     *
     * This method is final, for more information read https://github.com/myclabs/php-enum/issues/4
     * @param static $variable
     * @return bool
     */
    final public function equals(self $variable): bool
    {
        return $variable instanceof static
            && $this->getKey() === $variable->getKey()
            && $this->getValue() === $variable->getValue()
            && static::class === \get_class($variable);
    }

    /**
     * Returns the names (keys) of all constants in the Enum class
     *
     * @psalm-pure
     * @psalm-return list<string>
     */
    final public static function keys(): array
    {
        return \array_keys(self::toArray());
    }

    /**
     * Returns instances of the Enum class of all Enum constants
     *
     * @psalm-pure
     * @psalm-return array<string, static>
     * @return static[] Constant name in key, Enum instance in value
     */
    final public static function values(): array
    {
        $values = [];

        foreach (self::toArray() as $key => $value) {
            $values[$key] = self::getInstance($key, $value);
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
     * @return array Constant name in key, constant value in value
     */
    final public static function toArray(): array
    {
        $class = static::class;

        if (!isset(self::$cache[$class])) {
            /** @psalm-suppress ImpureMethodCall this reflection API usage has no side-effects here */
            $reflection = new \ReflectionClass($class);
            /** @psalm-suppress ImpureMethodCall this reflection API usage has no side-effects here */
            if (!$reflection->isFinal()) {
                throw new \ParseError("Class " . $class . " is not declared final");
            }
            /** @psalm-suppress ImpureMethodCall this reflection API usage has no side-effects here */
            self::$cache[$class] = $reflection->getConstants();
        }

        return self::$cache[$class];
    }

    /**
     * Check if is valid enum value
     *
     * @param $value
     * @psalm-param mixed $value
     * @psalm-pure
     * @psalm-assert-if-true T $value
     * @return bool
     */
    final public static function isValid($value): bool
    {
        return \in_array($value, static::toArray(), true);
    }

    /**
     * Asserts valid enum value
     *
     * @psalm-pure
     * @psalm-assert T $value
     * @param mixed $value
     */
    final public static function assertValidValue($value): void
    {
        if (!self::isValid($value)) {
            throw new \UnexpectedValueException("Value '$value' is not part of the enum " . static::class);
        }
    }

    /**
     * Check if is valid enum key
     *
     * @param string $key
     * @psalm-param string $key
     * @psalm-pure
     * @return bool
     */
    final public static function isValidKey(string $key): bool
    {
        $array = self::toArray();

        return isset($array[$key]) || \array_key_exists($key, $array);
    }

    /**
     * Return key for value
     *
     * @param mixed $value
     *
     * @psalm-param mixed $value
     * @psalm-pure
     * @return string|false
     */
    final public static function search($value)
    {
        return \array_search($value, static::toArray(), true);
    }

    /**
     * Returns a value when called statically like so: MyEnum::SOME_VALUE() given SOME_VALUE is a class constant
     *
     * @param string $name
     * @param array $arguments
     *
     * @return static
     * @throws \BadMethodCallException
     *
     * @psalm-pure
     */
    public static function __callStatic($name, $arguments)
    {
        $array = self::toArray();
        if (!isset($array[$name]) && !\array_key_exists($name, $array)) {
            $message = "No static method or enum constant '$name' in class " . static::class;
            throw new \BadMethodCallException($message);
        }
        return self::getInstance($name, $array[$name]);
    }

    /**
     * Specify data which should be serialized to JSON. This method returns data that can be serialized by json_encode()
     * natively.
     *
     * @return mixed
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @psalm-pure
     */
    public function jsonSerialize()
    {
        return $this->getValue();
    }
}
