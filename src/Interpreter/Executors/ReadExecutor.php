<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter\Executors;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\NodeExecutor;
use Phortugol\Interpreter\Runner;
use Phortugol\Parser\Nodes\ReadNode;

/**
 * @implements NodeExecutor<ReadNode>
 */
final class ReadExecutor implements NodeExecutor
{
    /**
     * @param ReadNode $node
     */
    public function execute(Node $node, Runner $runner): null
    {
        foreach ($node->identifiers as $name) {
            $runner->env->set($name, $this->coerce($runner->runtime->read()));
        }

        return null;
    }

    private function coerce(string $input): int | float | bool | string
    {
        $lower = strtolower(trim($input));

        if ($lower === 'verdadeiro') {
            return true;
        }

        if ($lower === 'falso') {
            return false;
        }

        $trimmed = trim($input);

        if (ctype_digit(ltrim($trimmed, '-')) && $trimmed !== '-') {
            return (int) $trimmed;
        }

        if (is_numeric($trimmed)) {
            return (float) $trimmed;
        }

        return $trimmed;
    }
}
