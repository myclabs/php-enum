<?php
/**
 * @link    http://github.com/myclabs/php-enum
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

namespace MyCLabs\Tests\Enum;

use MyCLabs\Enum\Enum;
use MyCLabs\Enum\Type;

class EnumTypeFixture extends Enum
{
    use Type {
        T as FOO;
        T as BAR;
        T as BAZ;
    }
}
