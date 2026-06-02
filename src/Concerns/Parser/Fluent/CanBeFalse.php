<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser\Fluent;

use Phortugol\Contracts\Node;
use Phortugol\Parser\Nodes\LiteralNode;

/**
 * @phpstan-property Node|null $condition
 */
trait CanBeFalse
{
    public function false(): static
    {
        $this->condition = new LiteralNode(false);

        return $this;
    }
}
