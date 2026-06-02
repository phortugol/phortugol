<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter\Executors;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\NodeExecutor;
use Phortugol\Exceptions\RuntimeException;
use Phortugol\Interpreter\Runner;
use Phortugol\Parser\Nodes\ArrayAssignNode;

/**
 * @implements NodeExecutor<ArrayAssignNode>
 */
final class ArrayAssignExecutor implements NodeExecutor
{
    /**
     * @param ArrayAssignNode $node
     */
    public function execute(Node $node, Runner $runner): null
    {
        $array = $runner->env->get($node->name);

        if (! is_array($array)) {
            throw new RuntimeException("Variable '{$node->name}' is not a vector");
        }

        $index = (int) $runner->execute($node->index);

        if (! array_key_exists($index, $array)) {
            throw new RuntimeException("Index {$index} out of bounds for vector '{$node->name}'");
        }

        $array[$index] = $runner->execute($node->value);
        $runner->env->set($node->name, $array);

        return null;
    }
}
