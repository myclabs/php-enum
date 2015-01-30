<?php
/**
 * @link    http://github.com/myclabs/php-enum
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

namespace MyCLabs\Enum;

/**
 * Class EnumFixture
 *
 * @method static EnumFixture FOO()
 * @method static EnumFixture BAR()
 * @method static EnumFixture NUMBER()
 *
 * @author Daniel Costa <danielcosta@gmail.com
 */
class EnumFixture extends Enum
{
    const FOO = "foo";
    const BAR = "bar";
    const NUMBER = 42;
}
