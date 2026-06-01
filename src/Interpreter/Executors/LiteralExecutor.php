<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter\Executors;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\NodeExecutor;
use Phortugol\Interpreter\Runner;
use Phortugol\Parser\Nodes\LiteralNode;

/**
 * @implements NodeExecutor<LiteralNode>
 */
final class LiteralExecutor implements NodeExecutor
{
    /**
     * @param LiteralNode $node
     */
    public function execute(Node $node, Runner $runner): int | float | string | bool
    {
        return $node->value;
    }
}
