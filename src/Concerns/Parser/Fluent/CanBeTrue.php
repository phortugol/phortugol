<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser\Fluent;

use Phortugol\Contracts\Node;
use Phortugol\Parser\Nodes\LiteralNode;

/**
 * @phpstan-property Node|null $condition
 */
trait CanBeTrue
{
    public function true(): static
    {
        $this->condition = new LiteralNode(true);

        return $this;
    }
}
