<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser\Fluent;

use Phortugol\Contracts\Node;

trait HasPortugueseBranchClauses
{
    public function entao(Node ...$statements): static
    {
        return $this->then(...$statements);
    }

    public function senao(Node ...$statements): static
    {
        return $this->otherwise(...$statements);
    }
}
