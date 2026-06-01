<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter\Executors;

use Phortugol\Concerns\HasCoercion;
use Phortugol\Contracts\Node;
use Phortugol\Contracts\NodeExecutor;
use Phortugol\Enums\TokenType;
use Phortugol\Exceptions\RuntimeException;
use Phortugol\Interpreter\Runner;
use Phortugol\Parser\Nodes\UnaryNode;

/**
 * @implements NodeExecutor<UnaryNode>
 */
final class UnaryExecutor implements NodeExecutor
{
    use HasCoercion;

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
}
