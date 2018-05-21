<?php
/**
 * @link    http://github.com/myclabs/php-enum
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */
namespace MyCLabs\Tests\Enum;

use MyCLabs\Enum\Enum;

/**
 * Class EnumEnhanced
 *
 * @method static EnumConflict FOO()
 * @method static EnumConflict BAR()
 *
 * @author Zarko Stankovic <stankovic.zarko@gmail.com>
 */
class EnumEnhanced extends Enum
{
    const FOO = "foo";
    const BAR = "bar";

    /**
     * @var string
     */
    private $description;

    /**
     * @param mixed  $value
     * @param string $description
     */
    public function __construct($value, $description)
    {
        parent::__construct($value);

        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
