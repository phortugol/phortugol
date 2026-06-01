<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter\Executors;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\NodeExecutor;
use Phortugol\Interpreter\Runner;
use Phortugol\Parser\Nodes\AssignNode;

/**
 * @implements NodeExecutor<AssignNode>
 */
final class AssignExecutor implements NodeExecutor
{
    /**
     * @param AssignNode $node
     */
    public function execute(Node $node, Runner $runner): null
    {
        $runner->env->set($node->name, $runner->execute($node->value));

        return null;
    }
}
