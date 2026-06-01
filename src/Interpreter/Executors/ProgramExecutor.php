<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter\Executors;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\NodeExecutor;
use Phortugol\Interpreter\Runner;
use Phortugol\Parser\Nodes\ProgramNode;

/**
 * @implements NodeExecutor<ProgramNode>
 */
final class ProgramExecutor implements NodeExecutor
{
    /**
     * @param ProgramNode $node
     */
    public function execute(Node $node, Runner $runner): null
    {
        foreach ($node->statements as $statement) {
            $runner->execute($statement);
        }

        return null;
    }
}
