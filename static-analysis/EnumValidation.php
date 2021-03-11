<?php

declare(strict_types=1);

namespace MyCLabs\Tests\Enum\StaticAnalysis;

use MyCLabs\Enum\Enum;

/**
 * @psalm-immutable
 * @psalm-template T of 'A'|'C'
 * @template-extends Enum<T>
 */
final class ValidationEnum extends Enum
{
    const A = 'A';
    const C = 'C';
}

/**
 * @psalm-pure
 * @param mixed $input
 * @psalm-return 'A'|'C'
 *
 * @psalm-suppress MixedReturnStatement
 * @psalm-suppress MixedInferredReturnType at the time of this writing, we did not yet find
 *                                         a proper approach to constraint input values through
 *                                         validation via static methods.
 */
function canValidateValue($input): string
{
    ValidationEnum::assertValidValue($input);

    return $input;
}

/**
 * @psalm-pure
 * @param mixed $input
 * @psalm-return 'A'|'C'
 *
 * @psalm-suppress MixedReturnStatement
 * @psalm-suppress MixedInferredReturnType at the time of this writing, we did not yet find
 *                                         a proper approach to constraint input values through
 *                                         validation via static methods.
 */
function canValidateValueThroughIsValid($input): string
{
    if (! ValidationEnum::isValid($input)) {
        throw new \InvalidArgumentException('Value not valid');
    }

    return $input;
}
