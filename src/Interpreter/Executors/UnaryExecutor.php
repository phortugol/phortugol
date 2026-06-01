<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter\Executors;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\NodeExecutor;
use Phortugol\Exceptions\RuntimeException;
use Phortugol\Interpreter\Runner;
use Phortugol\Lexer\TokenType;
use Phortugol\Parser\Nodes\UnaryNode;

/**
 * @implements NodeExecutor<UnaryNode>
 */
final class UnaryExecutor implements NodeExecutor
{
    /**
     * @param UnaryNode $node
     */
    public function execute(Node $node, Runner $runner): int | float | bool
    {
        $value = $runner->execute($node->right);

        return match ($node->operator) {
            TokenType::MINUS => -($this->asNumber($value)),
            TokenType::NAO   => ! ((bool) $value),
            default          => throw new RuntimeException("Unknown unary operator: {$node->operator->value}"),
        };
    }

    private function asNumber(mixed $value): int | float
    {
        if (is_int($value) || is_float($value)) {
            return $value;
        }

        throw new RuntimeException('Expected a number, got: ' . gettype($value));
    }
}
