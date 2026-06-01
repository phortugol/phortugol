<?php

declare(strict_types=1);

namespace Phortugol\Interpreter;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\Runtime;

final class Runner
{
    public function __construct(
        private readonly Runtime $runtime,
        private readonly ExecutorDispatcher $dispatcher,
    ) {
    }

    public static function create(Runtime $runtime): self
    {
        return new self($runtime, ExecutorDispatcher::default());
    }

    public function run(string $code): void
    {
        // TODO: tokenize → parse → execute
    }

    public function execute(Node $node): void
    {
        $this->dispatcher->dispatch($node, $this);
    }

    public function runtime(): Runtime
    {
        return $this->runtime;
    }
}
