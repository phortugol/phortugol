<?php

declare(strict_types=1);

namespace Phortugol\Interpreter;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\NodeExecutor;
use Phortugol\Exceptions\RuntimeException;

final class ExecutorDispatcher
{
    /** @param array<class-string<Node>, NodeExecutor> $executors */
    public function __construct(
        private readonly array $executors = [],
    ) {
    }

    public static function default(): self
    {
        return new self([
            // executors will be registered here as they are implemented
        ]);
    }

    public function dispatch(Node $node, Runner $runner): void
    {
        $class = $node::class;

        if (!isset($this->executors[$class])) {
            throw new RuntimeException("No executor registered for node type: {$class}");
        }

        $this->executors[$class]->execute($node, $runner);
    }
}
