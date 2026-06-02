<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser\Fluent;

use Phortugol\Contracts\Node;

/**
 * @phpstan-property list<Node> $thenBranch
 */
trait HasThenClause
{
    public function then(Node ...$statements): static
    {
        $this->thenBranch = array_values($statements);

        return $this;
    }
}
