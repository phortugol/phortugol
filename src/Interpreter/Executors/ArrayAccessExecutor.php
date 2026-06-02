<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter\Executors;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\NodeExecutor;
use Phortugol\Exceptions\RuntimeException;
use Phortugol\Interpreter\Runner;
use Phortugol\Parser\Nodes\ArrayAccessNode;

/**
 * @implements NodeExecutor<ArrayAccessNode>
 */
final class ArrayAccessExecutor implements NodeExecutor
{
    /**
     * @param ArrayAccessNode $node
     */
    public function execute(Node $node, Runner $runner): mixed
    {
        $array = $runner->env->get($node->name);

        if (! is_array($array)) {
            throw new RuntimeException("Variable '{$node->name}' is not a vector");
        }

        $index = (int) $runner->execute($node->index);

        if (! array_key_exists($index, $array)) {
            throw new RuntimeException("Index {$index} out of bounds for vector '{$node->name}'");
        }

        return $array[$index];
    }
}
