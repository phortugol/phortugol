<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Support\Parser;

use Phortugol\Contracts\Node;

/**
 * @phpstan-property list<Node>|null $elseBranch
 */
trait HasOtherwise
{
    public function otherwise(Node ...$statements): static
    {
        $this->elseBranch = array_values($statements);

        return $this;
    }
}
