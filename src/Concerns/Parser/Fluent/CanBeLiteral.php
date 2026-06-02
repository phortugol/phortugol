<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser\Fluent;

use Phortugol\Contracts\Node;
use Phortugol\Parser\Nodes\LiteralNode;

/**
 * @phpstan-property Node|null $condition
 */
trait CanBeLiteral
{
    public function literal(int | float | string | bool $value): static
    {
        $this->condition = new LiteralNode($value);

        return $this;
    }
}
