<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter\Executors;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\NodeExecutor;
use Phortugol\Interpreter\Runner;
use Phortugol\Parser\Nodes\IfNode;

/**
 * @implements NodeExecutor<IfNode>
 */
final class IfExecutor implements NodeExecutor
{
    /**
     * @param IfNode $node
     */
    public function execute(Node $node, Runner $runner): null
    {
        if ((bool) $runner->execute($node->condition)) {
            foreach ($node->thenBranch as $statement) {
                $runner->execute($statement);
            }
        } elseif ($node->elseBranch !== null) {
            foreach ($node->elseBranch as $statement) {
                $runner->execute($statement);
            }
        }

        return null;
    }
}
