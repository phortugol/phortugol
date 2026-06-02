<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser\Fluent;

use Phortugol\Contracts\Node;

/**
 * @phpstan-property Node|null $condition
 */
trait CanWhen
{
    use CanEvaluate;

    public function when(Node | int | float | string | bool $condition): static
    {
        $this->condition = $this->evaluate($condition);

        return $this;
    }

    public function quando(Node | int | float | string | bool $condition): static
    {
        return $this->when($condition);
    }
}
