<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter\Executors;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\NodeExecutor;
use Phortugol\Interpreter\ReturnSignal;
use Phortugol\Interpreter\Runner;
use Phortugol\Parser\Nodes\ReturnNode;

/**
 * @implements NodeExecutor<ReturnNode>
 */
final class ReturnExecutor implements NodeExecutor
{
    /**
     * @param  ReturnNode   $node
     * @throws ReturnSignal
     */
    public function execute(Node $node, Runner $runner): never
    {
        throw new ReturnSignal($runner->execute($node->value));
    }
}
