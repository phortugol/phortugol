<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser\Fluent;

use Phortugol\Contracts\Node;

/**
 * @phpstan-property list<Node>|null $elseBranch
 */
trait HasElseClause
{
    public function otherwise(Node ...$statements): static
    {
        $this->elseBranch = array_values($statements);

        return $this;
    }

    public function senao(Node ...$statements): static
    {
        return $this->otherwise(...$statements);
    }
}
