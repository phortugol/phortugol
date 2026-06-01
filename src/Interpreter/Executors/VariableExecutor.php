<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter\Executors;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\NodeExecutor;
use Phortugol\Interpreter\Runner;
use Phortugol\Parser\Nodes\VariableNode;

/**
 * @implements NodeExecutor<VariableNode>
 */
final class VariableExecutor implements NodeExecutor
{
    /**
     * @param VariableNode $node
     */
    public function execute(Node $node, Runner $runner): mixed
    {
        return $runner->env->get($node->name);
    }
}
