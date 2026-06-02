<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser\Fluent;

use Phortugol\Contracts\Node;

/**
 * @phpstan-property Node|null $from
 * @phpstan-property Node|null $to
 * @phpstan-property Node|null $step
 */
trait HasForRange
{
    use CanEvaluate;

    public function from(Node | int | float $value): static
    {
        $this->from = $this->evaluate($value);

        return $this;
    }

    public function to(Node | int | float $value): static
    {
        $this->to = $this->evaluate($value);

        return $this;
    }

    public function step(Node | int | float $value): static
    {
        $this->step = $this->evaluate($value);

        return $this;
    }

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
