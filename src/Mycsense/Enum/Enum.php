<?php
/**
 * @link    http://github.com/myc-sense/php-enum
 * @author  Matthieu Napoli <matthieu@mnapoli.fr>
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

namespace Mycsense\Enum;

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
        $reflection = new \ReflectionClass(get_called_class());
        return $reflection->getConstants();
    }

}
