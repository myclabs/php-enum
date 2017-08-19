<?php
/**
 * @link    http://github.com/myclabs/php-enum
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

namespace MyCLabs\Enum;

use BadMethodCallException;

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
     * Enum name
     *
     * @var string
     */
    private $name;

    /**
     * Enum value
     *
     * @var mixed
     */
    private $value;

    /**
     * Creates a new value of some type
     *
     * @param mixed $value
     */
    final private function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns the enum key (i.e. the constant name).
     *
     * @return mixed
     */
    public function getKey()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }

    /**
     * Register object in cache and trigger a notice if it already exists.
     */
    public function __wakeup()
    {
        $enum = EnumManager::get($this);
        if ($enum !== $this) {
            trigger_error("Enum is already initialized", E_USER_NOTICE);
        }
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
     * Returns instances of the Enum class of all Enum constants
     *
     * @return static[] Constant name in key, Enum instance in value
     */
    public static function values()
    {
        $values = array();

        foreach (static::toArray() as $name => $value) {
            $values[$name] = EnumManager::get(new static($name, $value));
        }

        return $values;
    }

    /**
     * Returns all possible values as an array
     *
     * @return array Constant name in key, constant value in value
     */
    public static function toArray()
    {
        return EnumManager::constants(new static(null, null));
    }

    /**
     * Check if is valid enum value
     *
     * @param $value
     *
     * @return bool
     */
    public static function isValid($value)
    {
        return in_array($value, static::toArray(), true);
    }

    /**
     * Check if is valid enum key
     *
     * @param $key
     *
     * @return bool
     */
    public static function isValidKey($key)
    {
        $array = static::toArray();

        return isset($array[$key]);
    }

    /**
     * Return key for value
     *
     * @param $value
     *
     * @return mixed
     */
    public static function search($value)
    {
        return array_search($value, static::toArray(), true);
    }

    /**
     * Returns Enum by value
     *
     * @return static
     */
    public static function fromValue($value)
    {
        $name = static::search($value);
        if ($name === false) {
            return null;
        }

        return EnumManager::get(new static($name, $value));
    }

    /**
     * Returns Enum by key
     *
     * @return static
     */
    public static function fromKey($name)
    {
        $array = static::toArray();
        if (isset($array[$name]) || array_key_exists($name, $array)) {
            return EnumManager::get(new static($name, $array[$name]));
        }

        return null;
    }

    /**
     * Returns a value when called statically like so: MyEnum::SOME_VALUE() given SOME_VALUE is a class constant
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return static
     * @throws BadMethodCallException
     */
    public static function __callStatic($name, $arguments)
    {
        $result = static::fromKey($name);

        if ($result === null) {
            $msg = "No static method or enum constant '$name' in class " . get_called_class();
            throw new BadMethodCallException($msg);
        }

        return $result;
    }
}
