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
     * toArray()
     */
    public function testToArray()
    {
        $values = EnumFixture::toArray();
        $expectedValues = array(
            "FOO"                   => EnumFixture::FOO,
            "BAR"                   => EnumFixture::BAR,
            "NUMBER"                => EnumFixture::NUMBER,
            "PROBLEMATIC_NUMBER"    => EnumFixture::PROBLEMATIC_NUMBER,
            "PROBLEMATIC_NULL"      => EnumFixture::PROBLEMATIC_NULL,
            "PROBLEMATIC_EMPTY_STRING"    => EnumFixture::PROBLEMATIC_EMPTY_STRING,
            "PROBLEMATIC_BOOLEAN_FALSE"    => EnumFixture::PROBLEMATIC_BOOLEAN_FALSE,
        );

        $this->assertSame($expectedValues, $values);
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
     * @dataProvider isValidProvider
     */
    public function testIsValid($value, $isValid)
    {
        $this->assertSame($isValid, EnumFixture::isValid($value));
    }

    public function isValidProvider() {
        return [
            /**
             * Valid values
             */
            ['foo', true],
            [42, true],
            [null, true],
            [0, true],
            ['', true],
            [false, true],
            /**
             * Invalid values
             */
            ['baz', false]
        ];
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
        /**
         * @see https://github.com/myclabs/php-enum/issues/9
         */
        $this->assertEquals(EnumFixture::PROBLEMATIC_NUMBER, EnumFixture::search(1));
    }
}
