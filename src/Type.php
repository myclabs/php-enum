<?php
/**
 * @link    http://github.com/myclabs/php-enum
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

namespace MyCLabs\Enum;

trait Type
{
    protected static function inspectTypes()
    {
        $obj = new \ReflectionClass(self::class);
        $typeSet = [];
        foreach ($obj->getTraitAliases() as $name => $text) {
            $typeSet[$name] = $name;
        }

        return $typeSet;
    }

    /**
     * @return static
     */
    public static function T()
    {
        $name = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0]['function'];

        return new static($name);
    }
}
