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
 */
abstract class Enum
{
    /**
     * Returns a value when called statically like so: MyEnum::SOME_VALUE() given SOME_VALUE is a class constant
     *
     * @param string $key
     *
     * @param array $arguments
     * @return static
     */
    final public static function __callStatic($key, $arguments = [])
    {
        print_r(self::$cache);
        print_r(self::$enums);

        return self::getEnum($key);
    }

    /**
     * Returns the name of this enum constant, exactly as declared in its
     * enum declaration.
     *
     * Most programmers should use the  __toString method in
     * preference to this one, as the toString method may return
     * a more user-friendly name.
     *
     * This method is designed primarily for
     * use in specialized situations where correctness depends on getting the
     * exact name, which will not vary from release to release.
     *
     * @return mixed name of this enum constant
     */
    public function name()
    {
        return static::search($this->value);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }

    /**
     * Returns true if the specified enum is equal to this
     *
     * @param Enum $enum the Enum to be compared for equality with this object.
     *
     * @return true if the specified enum is equal to this enum.
     */
    public function equals(Enum $enum)
    {
        return $this === $enum;
    }

    /**
     * @return string
     */
    public static function className()
    {
        return get_called_class();
    }

    /**
     * Returns the names (keys) of all constants in the Enum class
     *
     * @return array
     */
    public static function keys()
    {
        return array_keys(static::toArray());
    }

    /**
     * Creates a new value of some type
     *
     * @param mixed $value
     *
     * @throws \UnexpectedValueException if incompatible type is given.
     */
    protected function __construct($value)
    {
        echo "constructor called".PHP_EOL;
        if (!$this->isValid($value)) {
            throw new \UnexpectedValueException("Value '$value' is not part of the enum " . static::className());
        }

        $this->value = $value;
    }

    /**
     * get enum by key and init if did not init ent
     *
     * @param $key
     *
     * @return Enum
     */
    final static private function getEnum($key)
    {
        if(! self::isInit($key)) {
            $array = static::toArray();
            if (! array_key_exists($key, $array)) {
                throw new \BadMethodCallException("No static method or enum constant '{$key}' in class " . self::className());
            }

            self::$enums[self::getInternalKey($key)] = new static($array[$key]);
        }

        return self::$enums[self::getInternalKey($key)];
    }

    /**
     * checks is Enum already init
     * @param $key
     * @return bool
     */
    final static private function isInit($key)
    {
        return array_key_exists(self::getInternalKey($key), self::$enums);
    }

    /**
     * make internal enum key
     *
     * @param $key
     *
     * @return string
     */
    final static private function getInternalKey($key)
    {
        return static::className().$key;
    }

    /**
     * Returns all possible values as an array
     *
     * @return array Constant name in key, constant value in value
     */
    final static private function toArray()
    {
        $class = static::className();
        if (!array_key_exists($class, static::$cache)) {
            $reflection            = new \ReflectionClass($class);
            static::$cache[$class] = $reflection->getConstants();
        }

        return static::$cache[$class];
    }

    /**
     * Check if is valid enum value
     *
     * @param $value
     *
     * @return bool
     */
    final private function isValid($value)
    {
        return in_array($value, static::toArray(), true);
    }

    /**
     * Return key for value
     *
     * @param $value
     *
     * @return mixed
     */
    final private function search($value)
    {
        return array_search($value, static::toArray(), true);
    }


    /**
     * Enum value
     *
     * @var mixed
     */
    protected $value;

    /**
     * Store existing constants in a static cache per object.
     *
     * @var array
     */
    protected static $cache = [];

    /**
     * @var Enum[]
     */
    private static $enums = [];
}
