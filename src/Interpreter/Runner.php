<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\Runtime;
use Phortugol\Lexer\Tokenizer;
use Phortugol\Parser\Parser;

final class Runner
{
    private(set) Environment $env;

    public function __construct(
        public readonly Runtime $runtime,
        private readonly ExecutorDispatcher $dispatcher,
    ) {
        $this->env = new Environment();
    }

    public static function create(Runtime $runtime): self
    {
        return new self($runtime, ExecutorDispatcher::default());
    }

    public function run(string $code): void
    {
        $this->env = new Environment();

        $tokens = new Tokenizer($code)->tokenize();
        $ast = new Parser($tokens)->parse();

        $this->execute($ast);
    }

    public function execute(Node $node): mixed
    {
        return $this->dispatcher->dispatch($node, $this);
    }

    public function withEnvironment(Environment $environment, callable $callback): mixed
    {
        $previous = $this->env;

        $this->env = $environment;

        try {
            return $callback();
        } finally {
            $this->env = $previous;
        }
    }
}
