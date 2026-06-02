<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter\Executors;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\NodeExecutor;
use Phortugol\Interpreter\Runner;
use Phortugol\Interpreter\SubroutineValue;
use Phortugol\Parser\Nodes\FunctionNode;

/**
 * @implements NodeExecutor<FunctionNode>
 */
final class FunctionExecutor implements NodeExecutor
{
    /**
     * @param FunctionNode $node
     */
    public function execute(Node $node, Runner $runner): null
    {
        $runner->env->define($node->name, new SubroutineValue($node->parameters, $node->body));

        return null;
    }
}
