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
        $value = new EnumFixture(EnumFixture::FOO);
        $this->assertEquals(EnumFixture::FOO, $value->getValue());

        $value = new EnumFixture(EnumFixture::BAR);
        $this->assertEquals(EnumFixture::BAR, $value->getValue());

        $value = new EnumFixture(EnumFixture::NUMBER);
        $this->assertEquals(EnumFixture::NUMBER, $value->getValue());
    }

    /**
     * getKey()
     */
    public function testGetKey()
    {
        $value = new EnumFixture(EnumFixture::FOO);
        $this->assertEquals('FOO', $value->getKey());
        $this->assertNotEquals('BA', $value->getKey());
    }

    /**
     * @dataProvider invalidValueProvider
     */
    public function testCreatingEnumWithInvalidValue($value)
    {
        $this->setExpectedException(
            '\UnexpectedValueException',
            'Value \'' . $value . '\' is not part of the enum MyCLabs\Tests\Enum\EnumFixture'
        );

        new EnumFixture($value);
    }

    /**
     * Contains values not existing in EnumFixture
     * @return array
     */
    public function invalidValueProvider() {
        return [
            "string" => ['test'],
            "int" => [1234],
            "null" => [null],
        ];
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
        return [
            [EnumFixture::FOO, new EnumFixture(EnumFixture::FOO)],
            [EnumFixture::BAR, new EnumFixture(EnumFixture::BAR)],
            [(string) EnumFixture::NUMBER, new EnumFixture(EnumFixture::NUMBER)],
        ];
    }

    /**
     * keys()
     */
    public function testKeys()
    {
        $values = EnumFixture::keys();
        $this->assertInternalType("array", $values);
        $expectedValues = array(
            "FOO",
            "BAR",
            "NUMBER",
        );
        $this->assertEquals($expectedValues, $values);
    }

    /**
     * toArray()
     */
    public function testToArray()
    {
        $values = EnumFixture::toArray();
        $this->assertInternalType("array", $values);
        $expectedValues = array(
            "FOO"    => EnumFixture::FOO,
            "BAR"    => EnumFixture::BAR,
            "NUMBER" => EnumFixture::NUMBER,
        );
        $this->assertEquals($expectedValues, $values);
    }

    /**
     * __callStatic()
     */
    public function testStaticAccess()
    {
        $this->assertEquals(new EnumFixture(EnumFixture::FOO), EnumFixture::FOO());
        $this->assertEquals(new EnumFixture(EnumFixture::BAR), EnumFixture::BAR());
        $this->assertEquals(new EnumFixture(EnumFixture::NUMBER), EnumFixture::NUMBER());
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

    /**
     * isValid()
     */
    public function testIsValid()
    {
        $this->assertTrue(EnumFixture::isValid('foo'));
        $this->assertFalse(EnumFixture::isValid('baz'));
    }

    /**
     * ssValidKey()
     */
    public function testIsValidKey()
    {
        $this->assertTrue(EnumFixture::isValidKey('FOO'));
        $this->assertFalse(EnumFixture::isValidKey('BAZ'));
    }

    /**
     * search()
     */
    public function testSearch()
    {
        $this->assertEquals('FOO', EnumFixture::search('foo'));
        $this->assertNotEquals('FOO', EnumFixture::isValidKey('baz'));
    }
}
