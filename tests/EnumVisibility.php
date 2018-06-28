<?php

namespace MyCLabs\Tests\Enum;

use MyCLabs\Enum\Enum;

/**
 * Class EnumVisibility
 *
 * @method static EnumFixture FOO()
 * @method static EnumFixture BAR()
 * @method static EnumFixture BAZ()
 * @method static EnumFixture BUZ()
 *
 * @author Bram Van der Sype <bram.vandersype@gmail.com>
 */
class EnumVisibility extends Enum
{
    const FOO = 'foo';
    public const BAR = 'bar';
    protected const BAZ = 'baz';
    private const BUZ = 'buz';
}
