<?php
/**
 * @link    http://github.com/myclabs/php-enum
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

namespace MyCLabs\Tests\Enum;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 * @author Daniel Costa <danielcosta@gmail.com>
 * @author Mirosław Filip <mirfilip@gmail.com>
 */
class EnumTest extends \PHPUnit_Framework_TestCase
{
    /**
     * getValue()
     */
    public function testGetValue()
    {
        $value = EnumFixture::FOO();
        $this->assertEquals(EnumFixture::FOO, (string) $value);

        $value = EnumFixture::BAR();
        $this->assertEquals(EnumFixture::BAR, (string) $value);

        $value = EnumFixture::NUMBER();
        $this->assertEquals(EnumFixture::NUMBER, (string) $value);
    }

    /**
     * getKey()
     */
    public function testGetKey()
    {
        $value = EnumFixture::FOO();
        $this->assertEquals('FOO', $value->name());
        $this->assertNotEquals('BA', $value->name());
    }

    /**
     * __toString()
     * @dataProvider toStringProvider
     */
    public function testToString($expected, $enumObject)
    {
        $this->assertSame($expected, (string) $enumObject);
    }

    public function toStringProvider() {
        return array(
            array(EnumFixture::FOO, EnumFixture::FOO()),
            array(EnumFixture::BAR, EnumFixture::BAR()),
            array((string) EnumFixture::NUMBER, EnumFixture::NUMBER()),
        );
    }

    /**
     * keys()
     */
    public function testKeys()
    {
        $values = EnumFixture::keys();
        $expectedValues = array(
            "FOO",
            "BAR",
            "NUMBER",
            "PROBLEMATIC_NUMBER",
            "PROBLEMATIC_NULL",
            "PROBLEMATIC_EMPTY_STRING",
            "PROBLEMATIC_BOOLEAN_FALSE",
        );

        $this->assertSame($expectedValues, $values);
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage No static method or enum constant 'UNKNOWN' in class
     *                           UnitTest\MyCLabs\Enum\Enum\EnumFixture
     */
    public function testBadStaticAccess()
    {
        EnumFixture::UNKNOWN();
    }
}
