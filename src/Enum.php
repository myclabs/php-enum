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
 * @psalm-immutable
 * @psalm-consistent-constructor
 */
abstract class Enum implements \JsonSerializable
{
    /**
     * Enum value
     */
    private int|string $value;

    /**
     * Enum key, the constant name
     */
    private string $key;

    /**
     * Store existing constants in a static cache per object.
     *
     * @psalm-var array<class-string, array<string, int|string>>
     */
    private static array $cache = [];

    /**
     * Store existing constants in a static cache per object.
     *
     * @psalm-var array<class-string, array<int|string, string>>
     */
    private static array $reverseCache = [];

    /**
     * Store type of value, int or string. null value is for empty enums
     *
     * @psalm-var array<class-string, 'int'|'string'|'empty'>
     */
    private static array $typeCache = [];

    /**
     * Cache of instances of the Enum class
     *
     * @psalm-var array<class-string, array<string, static>>
     */
    private static array $instances = [];

    final private function __construct(string $key, int|string $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @psalm-pure
     */
    private static function getInstance(string $key, int|string $value): static
    {
        if (!isset(self::$instances[static::class][$key])) {
            return self::$instances[static::class][$key] = new static($key, $value);
        }

        return clone self::$instances[static::class][$key];
    }

    /**
     * @param int|string $value
     * @return static
     */
    final public static function from(int|string $value): static
    {
        return self::tryFrom($value) ?? throw new \UnexpectedValueException("Value '{$value}' is not part of the enum " . static::class);
    }

    final public static function tryFrom(int|string $value): ?static
    {
        $key = self::search($value);

        if ($key === false) {
            return null;
        }

        return self::getInstance($key, $value);
    }

    final public function getValue(): int|string
    {
        return $this->value;
    }

    final public function getKey(): string
    {
        return $this->key;
    }

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
    public static function keys(): array
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
    public static function values(): array
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
     * @psalm-return array<string, int|string>
     * @return array Constant name in key, constant value in value
     */
    final public static function toArray(): array
    {
        if (!isset(self::$cache[static::class])) {
            self::computeCache();
        }

        return self::$cache[static::class];
    }

    /**
     * @psalm-pure
     * @psalm-suppress ImpureStaticProperty
     *
     * @psalm-return array<int|string, string>
     * @return string[] Constant value in key, constant name in value
     */
    private static function toReverseArray(): array
    {
        if (!isset(self::$reverseCache[static::class])) {
            self::computeCache();
        }

        return self::$reverseCache[static::class];
    }

    /**
     * @psalm-pure
     * @psalm-suppress ImpureStaticProperty
     *
     * @psalm-return 'int'|'string'|'empty'
     */
    private static function getType(): string
    {
        if (!isset(self::$typeCache[static::class])) {
            self::computeCache();
        }

        return self::$typeCache[static::class];
    }

    /**
     * @psalm-pure
     * @psalm-suppress ImpureStaticProperty
     *
     * Compute cached values for the class using reflection
     */
    private static function computeCache(): void
    {
        /** @psalm-suppress ImpureMethodCall this reflection API usage has no side-effects here */
        $reflection = new \ReflectionClass(static::class);
        /** @psalm-suppress ImpureMethodCall this reflection API usage has no side-effects here */
        $constantsDefinition = $reflection->getConstants();
        /** @psalm-suppress ImpureMethodCall this reflection API usage has no side-effects here */
        if (!$reflection->isFinal()) {
            throw new \ParseError("Class " . static::class . " is not declared final");
        }

        $type = null;
        $reverseConstantsDefinition = [];
        /** @psalm-assert array<string, int|string> $constantsDefinition */
        foreach ($constantsDefinition as $key => $value) {
            if (is_int($value)) {
                if (!isset($type)) {
                    $type = 'int';
                } elseif ($type !== 'int') {
                    throw new \ParseError("Value for constant '{$key}' in class " . static::class . " is not int, even if previous value is int");
                }
            } elseif (is_string($value)) {
                if (!isset($type)) {
                    $type = 'string';
                } elseif ($type !== 'string') {
                    throw new \ParseError("Value for constant '{$key}' in class " . static::class . " is not string, even if previous value is string");
                }
            } else {
                throw new \ParseError("Value for constant '{$key}' is not int or string.");
            }

            if (\array_key_exists($value, $reverseConstantsDefinition)) {
                throw new \ParseError("Value '{$value}' is duplicated in the enum definition of class " . static::class);
            }

            $reverseConstantsDefinition[$value] = $key;
        }

        /** @psalm-suppress MixedPropertyTypeCoercion assert exists already of array<string, int|string> on $constantsDefinition */
        self::$cache[static::class] = $constantsDefinition;
        self::$reverseCache[static::class] = $reverseConstantsDefinition;
        self::$typeCache[static::class] = $type ?? 'empty';
    }

    /**
     * Check if is valid enum value
     *
     * @param int|string $value
     * @psalm-pure
     * @return bool
     */
    public static function isValid(int|string $value): bool
    {
        $reverseArray = self::toReverseArray();

        return isset($reverseArray[$value]);
    }

    /**
     * Asserts valid enum value
     *
     * @psalm-pure
     * @param int|string $value
     */
    public static function assertValidValue(int|string $value): void
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

        return isset($array[$key]);
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
    final public static function search(int|string $value): string|false
    {
        $type = self::getType();
        if (
            ($type === 'int' && !is_int($value))
            ||
            ($type === 'string' && !is_string($value))
            ||
            ($type === 'empty')
        ) {
            return false;
        }

        $reverseArray = self::toReverseArray();

        /** @psalm-suppress MixedArrayOffset this is already validated at this point */
        return $reverseArray[$value] ?? false;
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
        if (!isset($array[$name])) {
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
