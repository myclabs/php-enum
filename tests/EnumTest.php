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
        $value = EnumFixture::FOO();
        self::assertEquals(EnumFixture::FOO, $value->getValue());

        $value = EnumFixture::BAR();
        self::assertEquals(EnumFixture::BAR, $value->getValue());

        $value = EnumFixture::NUMBER();
        self::assertEquals(EnumFixture::NUMBER, $value->getValue());
    }

    /**
     * getKey()
     */
    public function testGetKey()
    {
        $value = EnumFixture::FOO();
        self::assertEquals('FOO', $value->getKey());
        self::assertNotEquals('BA', $value->getKey());
    }

    /**
     * @dataProvider invalidValueProvider
     * @param mixed $value
     */
    public function testFailToCreateEnumWithInvalidValueThroughNamedConstructor($value): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('is not part of the enum MyCLabs\Tests\Enum\EnumFixture');

        EnumFixture::from($value);
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
        self::assertSame($expected, (string) $enumObject);
    }

    public function toStringProvider()
    {
        return array(
            array(EnumFixture::FOO, EnumFixture::FOO()),
            array(EnumFixture::BAR, EnumFixture::BAR()),
            array((string) EnumFixture::NUMBER, EnumFixture::NUMBER()),
        );
    }

    /**
     * keys()
     */
    public function testStringKeys()
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

        self::assertSame($expectedValues, $values);
    }

    /**
     * values()
     */
    public function testStringValues()
    {
        $values = EnumFixture::values();
        $expectedValues = array(
            "FOO"                       => EnumFixture::FOO(),
            "BAR"                       => EnumFixture::BAR(),
            "NUMBER"                    => EnumFixture::NUMBER(),
            "PROBLEMATIC_NUMBER"        => EnumFixture::PROBLEMATIC_NUMBER(),
            "PROBLEMATIC_NULL"          => EnumFixture::PROBLEMATIC_NULL(),
            "PROBLEMATIC_EMPTY_STRING"  => EnumFixture::PROBLEMATIC_EMPTY_STRING(),
            "PROBLEMATIC_BOOLEAN_FALSE" => EnumFixture::PROBLEMATIC_BOOLEAN_FALSE(),
        );

        self::assertEquals($expectedValues, $values);
    }

    /**
     * toArray()
     */
    public function testStringEnumToArray()
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

        self::assertSame($expectedValues, $values);
    }

    /**
     * __callStatic()
     */
    public function testStaticAccess()
    {
        $this->assertEquals(EnumFixture::from(EnumFixture::FOO), EnumFixture::FOO());
        $this->assertEquals(EnumFixture::from(EnumFixture::BAR), EnumFixture::BAR());
        $this->assertEquals(EnumFixture::from(EnumFixture::NUMBER), EnumFixture::NUMBER());
        $this->assertNotSame(EnumFixture::NUMBER(), EnumFixture::NUMBER());
    }

    public function testBadStaticAccess()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('No static method or enum constant \'UNKNOWN\' in class ' . EnumFixture::class);

        EnumFixture::UNKNOWN();
    }

    /**
     * isValid()
     * @dataProvider isValidProvider
     */
    public function testIsValid($value, $isValid)
    {
        self::assertSame($isValid, EnumFixture::isValid($value));
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
        self::assertTrue(EnumFixture::isValidKey('FOO'));
        self::assertFalse(EnumFixture::isValidKey('BAZ'));
        self::assertTrue(EnumFixture::isValidKey('PROBLEMATIC_NULL'));
    }

    /**
     * search()
     * @see https://github.com/myclabs/php-enum/issues/13
     * @dataProvider searchProvider
     */
    public function testSearch($value, $expected)
    {
        self::assertSame($expected, EnumFixture::search($value));
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
        $foo = EnumFixture::from(EnumFixture::FOO);
        $number = EnumFixture::from(EnumFixture::NUMBER);
        $anotherFoo = EnumFixture::from(EnumFixture::FOO);

        self::assertTrue($foo->equals($foo));
        self::assertFalse($foo->equals($number));
        self::assertTrue($foo->equals($anotherFoo));
    }

    /**
     * equals()
     */
    public function testEqualsComparesProblematicValuesProperly()
    {
        $false = EnumFixture::from(EnumFixture::PROBLEMATIC_BOOLEAN_FALSE);
        $emptyString = EnumFixture::from(EnumFixture::PROBLEMATIC_EMPTY_STRING);
        $null = EnumFixture::from(EnumFixture::PROBLEMATIC_NULL);

        self::assertTrue($false->equals($false));
        self::assertFalse($false->equals($emptyString));
        self::assertFalse($emptyString->equals($null));
        self::assertFalse($null->equals($false));
    }

    /**
     * equals()
     */
    public function testEqualsConflictValues()
    {
        self::assertFalse(EnumFixture::FOO()->equals(EnumConflict::FOO()));
    }

    /**
     * jsonSerialize()
     */
    public function testJsonSerialize()
    {
        self::assertJsonEqualsJson('"foo"', json_encode(EnumFixture::from(EnumFixture::FOO)));
        self::assertJsonEqualsJson('"bar"', json_encode(EnumFixture::from(EnumFixture::BAR)));
        self::assertJsonEqualsJson('42', json_encode(EnumFixture::from(EnumFixture::NUMBER)));
        self::assertJsonEqualsJson('0', json_encode(EnumFixture::from(EnumFixture::PROBLEMATIC_NUMBER)));
        self::assertJsonEqualsJson('null', json_encode(EnumFixture::from(EnumFixture::PROBLEMATIC_NULL)));
        self::assertJsonEqualsJson('""', json_encode(EnumFixture::from(EnumFixture::PROBLEMATIC_EMPTY_STRING)));
        self::assertJsonEqualsJson('false', json_encode(EnumFixture::from(EnumFixture::PROBLEMATIC_BOOLEAN_FALSE)));
    }

    public function testNullableEnum()
    {
        self::assertNull(EnumFixture::PROBLEMATIC_NULL()->getValue());
        self::assertNull((EnumFixture::from(EnumFixture::PROBLEMATIC_NULL))->getValue());
        self::assertNull((EnumFixture::from(EnumFixture::PROBLEMATIC_NULL))->jsonSerialize());
    }

    private static function assertJsonEqualsJson(string $json1, string $json2): void
    {
        self::assertJsonStringEqualsJsonString($json1, $json2);
    }

    public function testSerialize()
    {
        // split string for Pretty CI: "Line exceeds 120 characters"
        $bin = '4f3a33303a224d79434c6162735c54657374735c456e756d5c456e756d4669787'.
            '4757265223a323a7b733a32343a22004d79434c6162735c456e756d5c456e756d0076616c7565223b733a333a22666f6f223b73'.
            '3a32323a22004d79434c6162735c456e756d5c456e756d006b6579223b733a333a22464f4f223b7d';

        self::assertEquals($bin, bin2hex(serialize(EnumFixture::FOO())));
    }

    public function testUnserialize()
    {
        // split string for Pretty CI: "Line exceeds 120 characters"
        $bin = '4f3a33303a224d79434c6162735c54657374735c456e756d5c456e756d4669787'.
            '4757265223a323a7b733a32343a22004d79434c6162735c456e756d5c456e756d0076616c7565223b733a333a22666f6f223b73'.
            '3a32323a22004d79434c6162735c456e756d5c456e756d006b6579223b733a333a22464f4f223b7d';

        /* @var $value EnumFixture */
        $value = unserialize(pack('H*', $bin));

        self::assertEquals(EnumFixture::FOO, $value->getValue());
        self::assertTrue(EnumFixture::FOO()->equals($value));
        self::assertTrue(EnumFixture::FOO() == $value);
    }

    /**
     * @see https://github.com/myclabs/php-enum/issues/95
     */
    public function testEnumMustBeFinal()
    {
        $this->expectException(\ParseError::class);
        $this->expectExceptionMessage("Class MyCLabs\Tests\Enum\NotFinalEnumFixture is not declared final");
        NotFinalEnumFixture::VALUE();
    }

    /**
     * @dataProvider isValidProvider
     */
    public function testAssertValidValueStringEnum($value, $isValid): void
    {
        if (!$isValid) {
            $this->expectException(\UnexpectedValueException::class);
            $this->expectExceptionMessage("Value '$value' is not part of the enum " . EnumFixture::class);
        }

        EnumFixture::assertValidValue($value);

        self::assertTrue(EnumFixture::isValid($value));
    }
}
