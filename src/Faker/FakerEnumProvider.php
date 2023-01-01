<?php

declare(strict_types=1);

namespace MyCLabs\Enum\Faker;

use Faker\Provider\Base;
use InvalidArgumentException;
use MyCLabs\Enum\Enum;

class FakerEnumProvider extends Base
{
    /**
     * A random instance of the enum you pass in.
     *
     * @param class-string $enum
     *
     * @return Enum
     */
    public static function randomEnum(string $enum): Enum
    {
        if (!is_subclass_of($enum, Enum::class)) {
            throw new InvalidArgumentException(
                sprintf(
                    'You have to pass the FQCN of a "%s" class but you passed "%s".',
                    Enum::class,
                    $enum
                )
            );
        }

        return static::randomElement($enum::values());
    }

    /**
     * A random value of the enum you pass in.
     *
     * @param class-string $enum
     *
     * @return string|int
     */
    public static function randomEnumValue(string $enum)
    {
        if (!is_subclass_of($enum, Enum::class)) {
            throw new InvalidArgumentException(
                sprintf(
                    'You have to pass the FQCN of a "%s" class but you passed "%s".',
                    Enum::class,
                    $enum
                )
            );
        }

        return static::randomElement($enum::toArray());
    }

    /**
     * A random label of the enum you pass in.
     *
     * @param class-string $enum
     *
     * @return string
     */
    public static function randomEnumKey(string $enum): string
    {
        if (!is_subclass_of($enum, Enum::class)) {
            throw new InvalidArgumentException(
                sprintf(
                    'You have to pass the FQCN of a "%s" class but you passed "%s".',
                    Enum::class,
                    $enum
                )
            );
        }

        return static::randomElement($enum::keys());
    }
}