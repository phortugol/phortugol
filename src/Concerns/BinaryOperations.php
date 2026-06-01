<?php

declare(strict_types = 1);

namespace Phortugol\Concerns;

use Phortugol\Exceptions\RuntimeException;

trait BinaryOperations
{
    use HasCoercion;

    private function add(mixed $left, mixed $right): int | float | string
    {
        if (is_string($left) || is_string($right)) {
            return ((string) $left) . ((string) $right);
        }

        return $this->asNumber($left) + $this->asNumber($right);
    }

    private function divide(mixed $left, mixed $right): int | float
    {
        $divisor = $this->asNumber($right);

        if ($divisor == 0) {
            throw new RuntimeException('Division by zero');
        }

        return $this->asNumber($left) / $divisor;
    }
}
