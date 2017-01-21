<?php
/**
 * @link    http://github.com/myclabs/php-enum
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

namespace MyCLabs\Enum;

use ReflectionObject;

/**
 * Enum instance manager
 *
 * @internal
 */
abstract class EnumManager
{
    /**
     * Store existing Enum instances.
     *
     * @var array
     */
    private static $instances = array();

    /**
     * Returns the Enum instance for the given prototype
     *
     * @return Enum
     */
    public static function get(Enum $enum)
    {
        $reflection = new ReflectionObject($enum);
        $class = $reflection->getName();
        $name = $enum->getKey();

        if (isset(self::$instances[$class][$name])) {
            return self::$instances[$class][$name];
        }

        self::$instances[$class][$name] = $enum;
        return $enum;
    }
}
