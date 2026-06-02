<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter\Executors;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\NodeExecutor;
use Phortugol\Interpreter\Runner;
use Phortugol\Parser\Nodes\ArrayDeclNode;

/**
 * @implements NodeExecutor<ArrayDeclNode>
 */
final class ArrayDeclExecutor implements NodeExecutor
{
    /**
     * @param ArrayDeclNode $node
     */
    public function execute(Node $node, Runner $runner): null
    {
        $start = (int) $runner->execute($node->startIndex);
        $end = (int) $runner->execute($node->endIndex);

        $runner->env->set($node->name, array_fill($start, $end - $start + 1, null));

        return null;
    }
}
