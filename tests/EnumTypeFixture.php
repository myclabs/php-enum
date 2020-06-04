<?php
/**
 * @link    http://github.com/myclabs/php-enum
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

namespace MyCLabs\Tests\Enum;

use MyCLabs\Enum\Enum;
use MyCLabs\Enum\EnumTrait;

class EnumTypeFixture extends Enum
{
    use EnumTrait {
        type as FOO;
        type as BAR;
        type as BAZ;
    }
}
