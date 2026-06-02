<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser\Fluent;

use Phortugol\Contracts\Node;

trait HasPortugueseCondition
{
    public function quando(Node | int | float | string | bool $condition): static
    {
        return $this->when($condition);
    }

    public function verdadeiro(): static
    {
        return $this->true();
    }

    public function falso(): static
    {
        return $this->false();
    }

    public function variavel(string $name): static
    {
        return $this->variable($name);
    }
}
