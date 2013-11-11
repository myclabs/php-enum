<?php
/**
 * @link    http://github.com/myclabs/php-enum
 * @author  Matthieu Napoli <matthieu@mnapoli.fr>
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

namespace MyCLabs\Enum;

/**
 * Base Enum class
 *
 * Create an enum by implementing this class and adding class constants.
 */
abstract class Enum
{

    /**
     * Enum value
     * @var mixed
     */
    protected $value;
    
    /**
     * Store instantiated reflection objects in a static cache.
     * @var array
     */
    protected static $reflectionCache = array();

    /**
     * Creates a new value of some type
     * @param mixed $value
     * @throws \UnexpectedValueException if incompatible type is given.
     */
    public function __construct($value)
    {
        $possibleValues = self::toArray();
        if (! in_array($value, $possibleValues)) {
            throw new \UnexpectedValueException("Value '$value' is not part of the enum " . get_called_class());
        }
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
     * @return string
     */
    public function __toString()
    {
        return (string) $this->value;
    }

    /**
     * Returns all possible values as an array
     * @return array Constant name in key, constant value in value
     */
    public static function toArray()
    {
        $calledClass = get_called_class();
        if(!array_key_exists($calledClass, self::$reflectionCache)) {
            self::$reflectionCache[$calledClass] = new \ReflectionClass($calledClass);
        }
        return $reflection->getConstants();
    }

    /**
     * Returns a value when called statically like so: MyEnum::SOME_VALUE() given SOME_VALUE is a class constant
     * @param string $name
     * @param array  $arguments
     * @return static
     * @throws \BadMethodCallException
     */
    public static function __callStatic($name, $arguments)
    {
        if (defined("static::$name")) {
            return new static(constant("static::$name"));
        }
        throw new \BadMethodCallException("No static method or enum constant '$name' in class " . get_called_class());
    }

}
