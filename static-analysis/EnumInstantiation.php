<?php

declare(strict_types=1);

namespace MyCLabs\Tests\Enum\StaticAnalysis;

use MyCLabs\Enum\Enum;

/**
 * @psalm-immutable
 * @psalm-template T of 'A'|'C'
 * @template-extends Enum<T>
 */
final class InstantiatedEnum extends Enum
{
    const A = 'A';
    const C = 'C';
}

/**
 * @psalm-pure
 * @psalm-return InstantiatedEnum<'A'>
 */
function canCallConstructorWithConstantValue(): InstantiatedEnum
{
    return new InstantiatedEnum('A');
}

/**
 * @psalm-pure
 * @psalm-return InstantiatedEnum<'C'>
 */
function canCallConstructorWithConstantReference(): InstantiatedEnum
{
    return new InstantiatedEnum(InstantiatedEnum::C);
}

/** @psalm-pure */
function canCallFromWithKnownValue(): InstantiatedEnum
{
    return InstantiatedEnum::from('C');
}

/** @psalm-pure */
function canCallFromWithUnknownValue(): InstantiatedEnum
{
    return InstantiatedEnum::from(123123);
}
