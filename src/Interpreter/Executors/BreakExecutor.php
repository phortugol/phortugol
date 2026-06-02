<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter\Executors;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\NodeExecutor;
use Phortugol\Interpreter\BreakSignal;
use Phortugol\Interpreter\Runner;
use Phortugol\Parser\Nodes\BreakNode;

/**
 * @implements NodeExecutor<BreakNode>
 */
final class BreakExecutor implements NodeExecutor
{
    /**
     * @param  BreakNode   $node
     * @throws BreakSignal
     */
    public function execute(Node $node, Runner $runner): never
    {
        throw new BreakSignal();
    }
}
