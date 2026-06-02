<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser\Fluent;

use Phortugol\Contracts\Node;

trait HasPortugueseForRange
{
    public function de(Node | int | float $value): static
    {
        return $this->from($value);
    }

    public function ate(Node | int | float $value): static
    {
        return $this->to($value);
    }

    public function passo(Node | int | float $value): static
    {
        return $this->step($value);
    }
}
