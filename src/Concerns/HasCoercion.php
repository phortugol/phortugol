<?php

declare(strict_types = 1);

namespace Phortugol\Concerns;

use Phortugol\Exceptions\RuntimeException;

trait HasCoercion
{
    private function asNumber(mixed $value): int | float
    {
        if (is_int($value) || is_float($value)) {
            return $value;
        }

        throw new RuntimeException('Expected a number, got: ' . gettype($value));
    }

    private function asInt(mixed $value): int
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_float($value)) {
            return (int) $value;
        }

        throw new RuntimeException('Expected an integer, got: ' . gettype($value));
    }
}
