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
 * @method static StringEnumFixture FOO()
 * @method static StringEnumFixture BAR()
 * @method static StringEnumFixture EMPTY()
 *
 * @author Daniel Costa <danielcosta@gmail.com>
 * @author Mirosław Filip <mirfilip@gmail.com>
 */
final class StringEnumFixture extends Enum
{
    const FOO = "foo";
    const BAR = "bar";
    const EMPTY = "";
}
