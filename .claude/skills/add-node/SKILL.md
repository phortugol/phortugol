---
description: >
  Add a new AST node to the Phortugol interpreter. Use when the user wants to implement
  a new Portugol statement or expression (e.g. para/fimpara, repita/ate, função, procedimento).
  Covers the Node class, Parser rule, Executor, Dispatcher registration, and tests.
---

# Add AST Node

Follow these steps in order. Complete each step before moving to the next.

## Step 1 — Create the Node class

Create `src/Parser/Nodes/<NodeName>Node.php`.

Rules:
- Must be `final readonly class`
- Must implement `Phortugol\Parser\Nodes\Node` (marker interface)
- Only constructor-promoted properties — no methods
- Property types must be as specific as possible

```php
<?php

declare(strict_types=1);

namespace Phortugol\Parser\Nodes;

final readonly class ExampleNode implements Node
{
    /**
     * @param Node[] $body
     */
    public function __construct(
        public readonly Node $condition,
        public readonly array $body,
    ) {}
}
```

## Step 2 — Add the Parser rule

Open `src/Parser/Parser.php` and add a private method to parse the new construct.
Follow the existing pattern: consume the opening token, parse sub-expressions
recursively, consume the closing token, return the node.

Wire the new method into `parseStatement()`:

```php
TokenType::NEW_KEYWORD => $this->parseNewConstruct(),
```

## Step 3 — Create the Executor

Create `src/Interpreter/Executors/<NodeName>Executor.php`:

```php
<?php

declare(strict_types=1);

namespace Phortugol\Interpreter\Executors;

use Phortugol\Interpreter\NodeExecutor;
use Phortugol\Interpreter\Runner;
use Phortugol\Parser\Nodes\ExampleNode;
use Phortugol\Parser\Nodes\Node;

final class ExampleExecutor implements NodeExecutor
{
    public function execute(Node $node, Runner $runner): void
    {
        assert($node instanceof ExampleNode);

        // implementation here
        // use $runner->execute($childNode) for child nodes
        // use $runner->runtime for I/O
        // use $runner->env for variables
    }
}
```

## Step 4 — Register in the Dispatcher

Open `src/Interpreter/ExecutorDispatcher.php` and add to `default()`:

```php
->register(ExampleNode::class, new ExampleExecutor())
```

## Step 5 — Write the tests

### Unit — Parser

`tests/Unit/Parser/ParserTest.php`: verify the correct AST node is produced.

```php
it('parses <construct name>', function (): void {
    $tokens = (new Tokenizer('<source>'))->tokenize();
    $ast    = (new Parser($tokens))->parse();

    expect($ast->statements[0])->toBeInstanceOf(ExampleNode::class);
});
```

### Feature — Runner

`tests/Feature/<ConstructName>Test.php`: verify the output of a full program.

```php
it('executes <construct name>', function (): void {
    $source  = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
          <snippet>
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    $runner  = Runner::create($runtime);
    $runner->run((new Parser((new Tokenizer($source))->tokenize()))->parse());

    expect($runtime->output)->toBe(['expected output']);
});
```

## Step 6 — Run the full suite

```bash
composer test
composer analyse
```

Both must pass before finishing.
