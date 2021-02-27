<?php
/**
 * @link    http://github.com/myclabs/php-enum
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

namespace MyCLabs\Tests\Enum;

use MyCLabs\Enum\Enum;

/**
 * Class EnumFixture
 *
 * @method static IntEnumFixture FIRST()
 * @method static IntEnumFixture SECOND()
 * @method static IntEnumFixture THIRD()
 *
 * @author Daniel Costa <danielcosta@gmail.com>
 * @author Mirosław Filip <mirfilip@gmail.com>
 */
final class IntEnumFixture extends Enum
{
    const FIRST = 0;
    const SECOND = 1;
    const THIRD = 2;
}
