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
class EnumTest extends \PHPUnit\Framework\TestCase
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
     * getLabel()
     */
    public function testGetLabel()
    {
        $foo = new EnumFixture(EnumFixture::FOO);
        $problematicBooleanFalse = new EnumFixture(EnumFixture::PROBLEMATIC_BOOLEAN_FALSE);

        $this->assertEquals('Foo', $foo->getLabel());
        $this->assertEquals('Problematic Boolean False', $problematicBooleanFalse->getLabel());
    }

    /**
     * getLabels()
     */
    public function testGetLabels()
    {
        $values = EnumFixture::getLabels();
        $expectedValues = [
            "Foo"                       => EnumFixture::FOO,
            "Bar"                       => EnumFixture::BAR,
            "Number"                    => EnumFixture::NUMBER,
            "Problematic Number"        => EnumFixture::PROBLEMATIC_NUMBER,
            "Problematic Null"          => EnumFixture::PROBLEMATIC_NULL,
            "Problematic Empty String"  => EnumFixture::PROBLEMATIC_EMPTY_STRING,
            "Problematic Boolean False" => EnumFixture::PROBLEMATIC_BOOLEAN_FALSE,
        ];

        $this->assertEquals($expectedValues, $values);
    }

    /**
     * @dataProvider invalidValueProvider
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage is not part of the enum MyCLabs\Tests\Enum\EnumFixture
     */
    public function testCreatingEnumWithInvalidValue($value)
    {
        new EnumFixture($value);
    }

    /**
     * Contains values not existing in EnumFixture
     * @return array
     */
    public function invalidValueProvider()
    {
        return array(
            "string" => array('test'),
            "int" => array(1234),
        );
    }

    /**
     * __toString()
     * @dataProvider toStringProvider
     */
    public function testToString($expected, $enumObject)
    {
        $this->assertSame($expected, (string) $enumObject);
    }

    public function toStringProvider()
    {
        return array(
            array(EnumFixture::FOO, new EnumFixture(EnumFixture::FOO)),
            array(EnumFixture::BAR, new EnumFixture(EnumFixture::BAR)),
            array((string) EnumFixture::NUMBER, new EnumFixture(EnumFixture::NUMBER)),
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
     * values()
     */
    public function testValues()
    {
        $values = EnumFixture::values();
        $expectedValues = array(
            "FOO"                       => new EnumFixture(EnumFixture::FOO),
            "BAR"                       => new EnumFixture(EnumFixture::BAR),
            "NUMBER"                    => new EnumFixture(EnumFixture::NUMBER),
            "PROBLEMATIC_NUMBER"        => new EnumFixture(EnumFixture::PROBLEMATIC_NUMBER),
            "PROBLEMATIC_NULL"          => new EnumFixture(EnumFixture::PROBLEMATIC_NULL),
            "PROBLEMATIC_EMPTY_STRING"  => new EnumFixture(EnumFixture::PROBLEMATIC_EMPTY_STRING),
            "PROBLEMATIC_BOOLEAN_FALSE" => new EnumFixture(EnumFixture::PROBLEMATIC_BOOLEAN_FALSE),
        );

        $this->assertEquals($expectedValues, $values);
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

    public function isValidProvider()
    {
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
     * isValidKey()
     */
    public function testIsValidKey()
    {
        $this->assertTrue(EnumFixture::isValidKey('FOO'));
        $this->assertFalse(EnumFixture::isValidKey('BAZ'));
        $this->assertTrue(EnumFixture::isValidKey('PROBLEMATIC_NULL'));
    }

    /**
     * search()
     * @see https://github.com/myclabs/php-enum/issues/13
     * @dataProvider searchProvider
     */
    public function testSearch($value, $expected)
    {
        $this->assertSame($expected, EnumFixture::search($value));
    }

    public function searchProvider()
    {
        return array(
            array('foo', 'FOO'),
            array(0, 'PROBLEMATIC_NUMBER'),
            array(null, 'PROBLEMATIC_NULL'),
            array('', 'PROBLEMATIC_EMPTY_STRING'),
            array(false, 'PROBLEMATIC_BOOLEAN_FALSE'),
            array('bar I do not exist', false),
            array(array(), false),
        );
    }

    /**
     * equals()
     */
    public function testEquals()
    {
        $foo = new EnumFixture(EnumFixture::FOO);
        $number = new EnumFixture(EnumFixture::NUMBER);
        $anotherFoo = new EnumFixture(EnumFixture::FOO);
        $objectOfDifferentClass = new \stdClass();
        $notAnObject = 'foo';

        $this->assertTrue($foo->equals($foo));
        $this->assertFalse($foo->equals($number));
        $this->assertTrue($foo->equals($anotherFoo));
        $this->assertFalse($foo->equals(null));
        $this->assertFalse($foo->equals($objectOfDifferentClass));
        $this->assertFalse($foo->equals($notAnObject));
    }

    /**
     * equals()
     */
    public function testEqualsComparesProblematicValuesProperly()
    {
        $false = new EnumFixture(EnumFixture::PROBLEMATIC_BOOLEAN_FALSE);
        $emptyString = new EnumFixture(EnumFixture::PROBLEMATIC_EMPTY_STRING);
        $null = new EnumFixture(EnumFixture::PROBLEMATIC_NULL);

        $this->assertTrue($false->equals($false));
        $this->assertFalse($false->equals($emptyString));
        $this->assertFalse($emptyString->equals($null));
        $this->assertFalse($null->equals($false));
    }

    /**
     * equals()
     */
    public function testEqualsConflictValues()
    {
        $this->assertFalse(EnumFixture::FOO()->equals(EnumConflict::FOO()));
    }

    /**
     * jsonSerialize()
     */
    public function testJsonSerialize()
    {
        $this->assertJsonEqualsJson('"foo"', json_encode(new EnumFixture(EnumFixture::FOO)));
        $this->assertJsonEqualsJson('"bar"', json_encode(new EnumFixture(EnumFixture::BAR)));
        $this->assertJsonEqualsJson('42', json_encode(new EnumFixture(EnumFixture::NUMBER)));
        $this->assertJsonEqualsJson('0', json_encode(new EnumFixture(EnumFixture::PROBLEMATIC_NUMBER)));
        $this->assertJsonEqualsJson('null', json_encode(new EnumFixture(EnumFixture::PROBLEMATIC_NULL)));
        $this->assertJsonEqualsJson('""', json_encode(new EnumFixture(EnumFixture::PROBLEMATIC_EMPTY_STRING)));
        $this->assertJsonEqualsJson('false', json_encode(new EnumFixture(EnumFixture::PROBLEMATIC_BOOLEAN_FALSE)));
    }

    public function testNullableEnum()
    {
        $this->assertNull(EnumFixture::PROBLEMATIC_NULL()->getValue());
        $this->assertNull((new EnumFixture(EnumFixture::PROBLEMATIC_NULL))->getValue());
        $this->assertNull((new EnumFixture(EnumFixture::PROBLEMATIC_NULL))->jsonSerialize());
    }

    public function testBooleanEnum()
    {
        $this->assertFalse(EnumFixture::PROBLEMATIC_BOOLEAN_FALSE()->getValue());
        $this->assertFalse((new EnumFixture(EnumFixture::PROBLEMATIC_BOOLEAN_FALSE))->jsonSerialize());
    }

    public function testConstructWithSameEnumArgument()
    {
        $enum = new EnumFixture(EnumFixture::FOO);

        $enveloped = new EnumFixture($enum);

        $this->assertEquals($enum, $enveloped);
    }

    private function assertJsonEqualsJson($json1, $json2)
    {
        $this->assertJsonStringEqualsJsonString($json1, $json2);
    }

    public function testSerialize()
    {
        // split string for Pretty CI: "Line exceeds 120 characters"
        $bin = '4f3a33303a224d79434c6162735c54657374735c456e756d5c456e756d4669787'.
            '4757265223a313a7b733a383a22002a0076616c7565223b733a333a22666f6f223b7d';

        $this->assertEquals($bin, bin2hex(serialize(EnumFixture::FOO())));
    }

    public function testUnserialize()
    {
        // split string for Pretty CI: "Line exceeds 120 characters"
        $bin = '4f3a33303a224d79434c6162735c54657374735c456e756d5c456e756d4669787'.
            '4757265223a313a7b733a383a22002a0076616c7565223b733a333a22666f6f223b7d';

        /* @var $value EnumFixture */
        $value = unserialize(pack('H*', $bin));

        $this->assertEquals(EnumFixture::FOO, $value->getValue());
        $this->assertTrue(EnumFixture::FOO()->equals($value));
    }

    /**
     * @see https://github.com/myclabs/php-enum/issues/95
     */
    public function testEnumValuesInheritance()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage("Value 'value' is not part of the enum MyCLabs\Tests\Enum\EnumFixture");
        $inheritedEnumFixture = InheritedEnumFixture::VALUE();
        new EnumFixture($inheritedEnumFixture);
    }
}
