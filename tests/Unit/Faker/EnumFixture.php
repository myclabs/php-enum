<?php

declare(strict_types=1);

namespace MyCLabs\Tests\Enum\Unit\Faker;

use MyCLabs\Enum\Enum;

/**
 * @method static self TESTED()
 * @method static self UNTESTED()
 */
class EnumFixture extends Enum
{
    private const TESTED = 'tested';

    private const UNTESTED = 'untested';
}