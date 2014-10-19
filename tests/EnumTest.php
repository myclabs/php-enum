<?php
/**
 * @link    http://github.com/myclabs/php-enum
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

namespace MyCLabs\Enum;

/**
 * Enum test
 *
 * @package MyCLabs\Enum
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
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
     * @expectedException \UnexpectedValueException
     */
    public function testInvalidValueString()
    {
        new EnumFixture("test");
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testInvalidValueInt()
    {
        new EnumFixture(1234);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testInvalidValueEmpty()
    {
        new EnumFixture(null);
    }

    /**
     * __toString()
     */
    public function testToString()
    {
        $value = new EnumFixture(EnumFixture::FOO);
        $this->assertEquals(EnumFixture::FOO, (string) $value);

        $value = new EnumFixture(EnumFixture::BAR);
        $this->assertEquals(EnumFixture::BAR, (string) $value);

        $value = new EnumFixture(EnumFixture::NUMBER);
        $this->assertEquals((string) EnumFixture::NUMBER, (string) $value);
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
     * values()
     */
    public function testValues()
    {
        $values = EnumFixture::values();
        $this->assertInternalType("array", $values);
        $expectedValues = array(
            "FOO"    => EnumFixture::FOO,
            "BAR"    => EnumFixture::BAR,
            "NUMBER" => EnumFixture::NUMBER,
        );
        $this->assertEquals($expectedValues, $values);
    }

    /**
     * toArray()
     */
    public function testToArray()
    {
        $this->assertEquals(EnumFixture::values(), EnumFixture::toArray());
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
