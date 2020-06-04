<?php
/**
 * @link    http://github.com/myclabs/php-enum
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

namespace MyCLabs\Tests\Enum;

class EnumTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * equals()
     */
    public function testEquals()
    {
        $foo = EnumTypeFixture::FOO();
        $anotherFoo = EnumTypeFixture::FOO();
        $bar = EnumTypeFixture::BAR();
        $objectOfDifferentClass = new \stdClass();
        $notAnObject = 'foo';

        $this->assertTrue($foo->equals($foo));
        $this->assertFalse($foo->equals($bar));
        $this->assertTrue($foo->equals($anotherFoo));
        $this->assertFalse($foo->equals(null));
        $this->assertFalse($foo->equals($objectOfDifferentClass));
        $this->assertFalse($foo->equals($notAnObject));
    }

    /**
     * getValue()
     */
    public function testGetValue()
    {
        $value = EnumTypeFixture::FOO();
        $this->assertEquals('FOO', $value->getValue());

        $value = EnumTypeFixture::BAR();
        $this->assertEquals('BAR', $value->getValue());
    }

    /**
     * getKey()
     */
    public function testGetKey()
    {
        $value = EnumTypeFixture::FOO();
        $this->assertEquals('FOO', $value->getKey());
        $this->assertNotEquals('BAR', $value->getKey());
    }
}
