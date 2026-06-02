<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser\Fluent;

use Phortugol\Contracts\Node;
use Phortugol\Parser\Nodes\LiteralNode;

trait CanEvaluate
{
    private function evaluate(Node | int | float | string | bool $value): Node
    {
        return $value instanceof Node ? $value : new LiteralNode($value);
    }
}
