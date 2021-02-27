<?php


namespace MyCLabs\Tests\Enum;

use MyCLabs\Enum\Enum;

/**
 * Class InheritedEnumFixture.
 * @package MyCLabs\Tests\Enum
 *
 * @method static NotFinalEnumFixture VALUE()
 */
class NotFinalEnumFixture extends Enum
{
    const VALUE = 'value';
}
