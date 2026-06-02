<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter\Executors;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\NodeExecutor;
use Phortugol\Interpreter\Runner;
use Phortugol\Parser\Nodes\WriteNode;

/**
 * @implements NodeExecutor<WriteNode>
 */
final class WriteExecutor implements NodeExecutor
{
    /**
     * @param WriteNode $node
     */
    public function execute(Node $node, Runner $runner): null
    {
        $parts = [];

        foreach ($node->expressions as $expression) {
            $parts[] = $this->stringify($runner->execute($expression));
        }

        $text = implode('', $parts);
        $runner->runtime->write($node->newline ? $text . "\n" : $text);

        return null;
    }

    private function stringify(mixed $value): string
    {
        return match (true) {
            is_bool($value) => $value ? 'verdadeiro' : 'falso',
            is_null($value) => '',
            default         => (string) $value,
        };
    }
}
