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
        $value = StringEnumFixture::FOO();
        self::assertEquals(StringEnumFixture::FOO, $value->getValue());

        $value = StringEnumFixture::BAR();
        self::assertEquals(StringEnumFixture::BAR, $value->getValue());

        $value = StringEnumFixture::EMPTY();
        self::assertEquals(StringEnumFixture::EMPTY, $value->getValue());

        $value = IntEnumFixture::FIRST();
        self::assertEquals(IntEnumFixture::FIRST, $value->getValue());

        $value = IntEnumFixture::SECOND();
        self::assertEquals(IntEnumFixture::SECOND, $value->getValue());

        $value = IntEnumFixture::THIRD();
        self::assertEquals(IntEnumFixture::THIRD, $value->getValue());
    }

    /**
     * getKey()
     */
    public function testGetKey()
    {
        $value = StringEnumFixture::FOO();
        self::assertEquals('FOO', $value->getKey());
        self::assertNotEquals('BA', $value->getKey());
    }

    /**
     * @dataProvider invalidValueProviderForString
     * @param mixed $value
     */
    public function testFailToCreateStringEnumWithInvalidValueThroughNamedConstructor($value): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('is not part of the enum MyCLabs\Tests\Enum\StringEnumFixture');

        StringEnumFixture::from($value);
    }

    /**
     * Contains values not existing in EnumFixture
     * @return array
     */
    public function invalidValueProviderForString()
    {
        return array(
            "string" => array('test'),
            "int" => array(1234),
            "int0" => array(0),
        );
    }

    /**
     * @dataProvider invalidValueProviderForInt
     * @param mixed $value
     */
    public function testFailToCreateIntEnumWithInvalidValueThroughNamedConstructor($value): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('is not part of the enum MyCLabs\Tests\Enum\IntEnumFixture');

        IntEnumFixture::from($value);
    }

    /**
     * Contains values not existing in EnumFixture
     * @return array
     */
    public function invalidValueProviderForInt()
    {
        return array(
            "string" => array('test'),
            "int" => array(1234),
            "emptystring" => array(''),
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
            array(StringEnumFixture::FOO, StringEnumFixture::FOO()),
            array(StringEnumFixture::BAR, StringEnumFixture::BAR()),
            array((string) IntEnumFixture::SECOND, IntEnumFixture::SECOND()),
            array((string) IntEnumFixture::FIRST, IntEnumFixture::FIRST()),
        );
    }

    /**
     * keys()
     */
    public function testStringKeys()
    {
        $values = StringEnumFixture::keys();
        $expectedValues = array(
            "FOO",
            "BAR",
            "EMPTY",
        );

        self::assertSame($expectedValues, $values);
    }

    /**
     * keys()
     */
    public function testIntKeys()
    {
        $values = IntEnumFixture::keys();
        $expectedValues = array(
            "FIRST",
            "SECOND",
            "THIRD",
        );

        self::assertSame($expectedValues, $values);
    }

    /**
     * values()
     */
    public function testStringValues()
    {
        $values = StringEnumFixture::values();
        $expectedValues = array(
            "FOO"                       => StringEnumFixture::FOO(),
            "BAR"                       => StringEnumFixture::BAR(),
            "EMPTY"                     => StringEnumFixture::EMPTY(),
        );

        self::assertEquals($expectedValues, $values);
    }

    /**
     * values()
     */
    public function testIntValues()
    {
        $values = IntEnumFixture::values();
        $expectedValues = array(
            "FIRST"                      => IntEnumFixture::FIRST(),
            "SECOND"                     => IntEnumFixture::SECOND(),
            "THIRD"                      => IntEnumFixture::THIRD(),
        );

        self::assertEquals($expectedValues, $values);
    }

    /**
     * toArray()
     */
    public function testStringEnumToArray()
    {
        $values = StringEnumFixture::toArray();
        $expectedValues = array(
            "FOO"                   => StringEnumFixture::FOO,
            "BAR"                   => StringEnumFixture::BAR,
            "EMPTY"                 => StringEnumFixture::EMPTY,
        );

        self::assertSame($expectedValues, $values);
    }

    /**
     * toArray()
     */
    public function testIntEnumToArray()
    {
        $values = IntEnumFixture::toArray();
        $expectedValues = array(
            "FIRST"                 => IntEnumFixture::FIRST,
            "SECOND"                => IntEnumFixture::SECOND,
            "THIRD"                 => IntEnumFixture::THIRD,
        );

        self::assertSame($expectedValues, $values);
    }

    /**
     * __callStatic()
     */
    public function testStaticAccess()
    {
        self::assertEquals(StringEnumFixture::from(StringEnumFixture::FOO), StringEnumFixture::FOO());
        self::assertEquals(StringEnumFixture::from(StringEnumFixture::BAR), StringEnumFixture::BAR());
        self::assertEquals(StringEnumFixture::from(StringEnumFixture::EMPTY), StringEnumFixture::EMPTY());
        self::assertNotSame(StringEnumFixture::FOO(), StringEnumFixture::FOO());

        self::assertEquals(IntEnumFixture::from(IntEnumFixture::FIRST), IntEnumFixture::FIRST());
        self::assertEquals(IntEnumFixture::from(IntEnumFixture::SECOND), IntEnumFixture::SECOND());
        self::assertEquals(IntEnumFixture::from(IntEnumFixture::THIRD), IntEnumFixture::THIRD());
        self::assertNotSame(IntEnumFixture::FIRST(), IntEnumFixture::FIRST());

    }

    public function testBadStaticAccess()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('No static method or enum constant \'UNKNOWN\' in class ' . StringEnumFixture::class);

        StringEnumFixture::UNKNOWN();
    }

    /**
     * isValid()
     * @dataProvider isValidStringEnumProvider
     */
    public function testIsValidStringEnum($value, $isValid)
    {
        self::assertSame($isValid, StringEnumFixture::isValid($value));
    }

    public function isValidStringEnumProvider()
    {
        return [
            /**
             * Valid values
             */
            ['foo', true],
            ['', true],
            /**
             * Invalid values
             */
            ['baz', false],
            [0, false],
            [1, false],
        ];
    }

    /**
     * isValid()
     * @dataProvider isValidIntEnumProvider
     */
    public function testIsValidIntEnum($value, $isValid)
    {
        self::assertSame($isValid, IntEnumFixture::isValid($value));
    }

    public function isValidIntEnumProvider()
    {
        return [
            /**
             * Valid values
             */
            [0, true],
            [1, true],
            /**
             * Invalid values
             */
            ['baz', false],
            [42, false],
            ['', false],
        ];
    }

    /**
     * isValidKey()
     */
    public function testIsValidKey()
    {
        self::assertTrue(StringEnumFixture::isValidKey('FOO'));
        self::assertFalse(StringEnumFixture::isValidKey('BAZ'));

        self::assertTrue(IntEnumFixture::isValidKey('FIRST'));
        self::assertFalse(IntEnumFixture::isValidKey('FOURTH'));
    }

    /**
     * search()
     * @dataProvider searchStringEnumProvider
     */
    public function testStringEnumSearch($value, $expected)
    {
        self::assertSame($expected, StringEnumFixture::search($value));
    }

    public function searchStringEnumProvider()
    {
        return array(
            array('foo', 'FOO'),
            array('', 'EMPTY'),
            array('bar I do not exist', false),
            array(0, false),
            array(42, false),
        );
    }

    /**
     * search()
     * @dataProvider searchIntEnumProvider
     */
    public function testIntEnumSearch($value, $expected)
    {
        self::assertSame($expected, IntEnumFixture::search($value));
    }

    public function searchIntEnumProvider()
    {
        return array(
            array(0, 'FIRST'),
            array(1, 'SECOND'),
            array(42, false),
            array('', false),
        );
    }

    /**
     * equals()
     */
    public function testEquals()
    {
        $foo = StringEnumFixture::from(StringEnumFixture::FOO);
        $anotherFoo = StringEnumFixture::from(StringEnumFixture::FOO);
        $empty = StringEnumFixture::from(StringEnumFixture::EMPTY);
        $first = IntEnumFixture::from(IntEnumFixture::FIRST);
        $second = IntEnumFixture::from(IntEnumFixture::SECOND);
        $secondTwice = IntEnumFixture::from(IntEnumFixture::SECOND);

        self::assertTrue($foo->equals($foo));
        self::assertFalse($foo->equals($empty));
        self::assertTrue($foo->equals($anotherFoo));
        self::assertFalse($foo->equals($first));
        self::assertFalse($empty->equals($first));
        self::assertFalse($first->equals($empty));
        self::assertFalse($first->equals($second));
        self::assertTrue($second->equals($secondTwice));
    }

    /**
     * equals()
     */
    public function testEqualsConflictValues()
    {
        self::assertFalse(StringEnumFixture::FOO()->equals(EnumConflict::FOO()));
    }

    /**
     * jsonSerialize()
     */
    public function testJsonSerialize()
    {
        self::assertJsonEqualsJson('"foo"', json_encode(StringEnumFixture::from(StringEnumFixture::FOO)));
        self::assertJsonEqualsJson('"bar"', json_encode(StringEnumFixture::from(StringEnumFixture::BAR)));
        self::assertJsonEqualsJson('""', json_encode(StringEnumFixture::from(StringEnumFixture::EMPTY)));
        self::assertJsonEqualsJson('0', json_encode(IntEnumFixture::from(IntEnumFixture::FIRST)));
        self::assertJsonEqualsJson('1', json_encode(IntEnumFixture::from(IntEnumFixture::SECOND)));
        self::assertJsonEqualsJson('2', json_encode(IntEnumFixture::from(IntEnumFixture::THIRD)));
    }

    private static function assertJsonEqualsJson(string $json1, string $json2): void
    {
        self::assertJsonStringEqualsJsonString($json1, $json2);
    }

    public function testSerialize()
    {
        // split string for Pretty CI: "Line exceeds 120 characters"
        $bin = '4f3a33363a224d79434c6162735c54657374735c456e756d5c537472696e67456e756d4669787'.
            '4757265223a323a7b733a32343a22004d79434c6162735c456e756d5c456e756d0076616c7565223b733a333a22666f6f223b73'.
            '3a32323a22004d79434c6162735c456e756d5c456e756d006b6579223b733a333a22464f4f223b7d';

        self::assertEquals($bin, bin2hex(serialize(StringEnumFixture::FOO())));
    }

    public function testUnserialize()
    {
        // split string for Pretty CI: "Line exceeds 120 characters"
        $bin = '4f3a33363a224d79434c6162735c54657374735c456e756d5c537472696e67456e756d4669787'.
            '4757265223a323a7b733a32343a22004d79434c6162735c456e756d5c456e756d0076616c7565223b733a333a22666f6f223b73'.
            '3a32323a22004d79434c6162735c456e756d5c456e756d006b6579223b733a333a22464f4f223b7d';

        /* @var $value StringEnumFixture */
        $value = unserialize(pack('H*', $bin));

        self::assertEquals(StringEnumFixture::FOO, $value->getValue());
        self::assertTrue(StringEnumFixture::FOO()->equals($value));
        self::assertTrue(StringEnumFixture::FOO() == $value);
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
     * @dataProvider isValidStringEnumProvider
     */
    public function testAssertValidValueStringEnum($value, $isValid): void
    {
        if (!$isValid) {
            $this->expectException(\UnexpectedValueException::class);
            $this->expectExceptionMessage("Value '$value' is not part of the enum " . StringEnumFixture::class);
        }

        StringEnumFixture::assertValidValue($value);

        self::assertTrue(StringEnumFixture::isValid($value));
    }

    /**
     * @dataProvider isValidIntEnumProvider
     */
    public function testAssertValidValueIntEnum($value, $isValid): void
    {
        if (!$isValid) {
            $this->expectException(\UnexpectedValueException::class);
            $this->expectExceptionMessage("Value '$value' is not part of the enum " . IntEnumFixture::class);
        }

        IntEnumFixture::assertValidValue($value);

        self::assertTrue(IntEnumFixture::isValid($value));
    }
}
