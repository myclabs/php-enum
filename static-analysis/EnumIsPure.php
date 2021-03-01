<?php

declare(strict_types=1);

namespace MyCLabs\Tests\Enum\StaticAnalysis;

use MyCLabs\Enum\Enum;

/**
 * @method static PureEnum A()
 * @method static PureEnum C()
 *
 * @psalm-immutable
 * @psalm-template T of 'A'|'B'
 * @template-extends Enum<T>
 */
final class PureEnum extends Enum
{
    const A = 'A';
    const C = 'C';
}

/** @psalm-pure */
function enumFetchViaMagicMethodIsPure(): PureEnum
{
    return PureEnum::A();
}

/** @psalm-pure */
function enumFetchViaExplicitMagicCallIsPure(): PureEnum
{
    return PureEnum::__callStatic('A', []);
}
