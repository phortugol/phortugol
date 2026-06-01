<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter\Executors;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\NodeExecutor;
use Phortugol\Exceptions\RuntimeException;
use Phortugol\Interpreter\Runner;
use Phortugol\Lexer\TokenType;
use Phortugol\Parser\Nodes\BinaryNode;

/**
 * @implements NodeExecutor<BinaryNode>
 */
final class BinaryExecutor implements NodeExecutor
{
    /**
     * @param BinaryNode $node
     */
    public function execute(Node $node, Runner $runner): int | float | string | bool
    {
        $left = $runner->execute($node->left);
        $right = $runner->execute($node->right);

        return match ($node->operator) {
            TokenType::PLUS          => $this->add($left, $right),
            TokenType::MINUS         => $this->asNumber($left) - $this->asNumber($right),
            TokenType::STAR          => $this->asNumber($left) * $this->asNumber($right),
            TokenType::SLASH         => $this->divide($left, $right),
            TokenType::DIV           => intdiv($this->asInt($left), $this->asInt($right)),
            TokenType::MOD           => $this->asInt($left) % $this->asInt($right),
            TokenType::EQUAL         => $left === $right,
            TokenType::NOT_EQUAL     => $left !== $right,
            TokenType::LESS          => $this->asNumber($left) < $this->asNumber($right),
            TokenType::LESS_EQUAL    => $this->asNumber($left) <= $this->asNumber($right),
            TokenType::GREATER       => $this->asNumber($left) > $this->asNumber($right),
            TokenType::GREATER_EQUAL => $this->asNumber($left) >= $this->asNumber($right),
            TokenType::E             => (bool) $left && (bool) $right,
            TokenType::OU            => (bool) $left || (bool) $right,
            default                  => throw new RuntimeException("Unknown binary operator: {$node->operator->value}"),
        };
    }

    private function add(mixed $left, mixed $right): int | float | string
    {
        if (is_string($left) || is_string($right)) {
            return ((string) $left) . ((string) $right);
        }

        return $this->asNumber($left) + $this->asNumber($right);
    }

    private function divide(mixed $left, mixed $right): int | float
    {
        $divisor = $this->asNumber($right);

        if ($divisor == 0) {
            throw new RuntimeException('Division by zero');
        }

        return $this->asNumber($left) / $divisor;
    }

    private function asNumber(mixed $value): int | float
    {
        if (is_int($value) || is_float($value)) {
            return $value;
        }

        throw new RuntimeException('Expected a number, got: ' . gettype($value));
    }

    private function asInt(mixed $value): int
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_float($value)) {
            return (int) $value;
        }

        throw new RuntimeException('Expected an integer, got: ' . gettype($value));
    }
}
