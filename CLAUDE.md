# Phortugol

A Portugol interpreter written in PHP 8.5. The name is a play on **PH**P + Por**tugol**.

## What This Is

A standalone PHP package that tokenizes, parses, and executes Portugol (a Portuguese pseudocode language used in Brazilian CS education, as seen in VisuAlg).

The package is **framework-agnostic**. It has zero Laravel or Symfony dependencies. Integrations live in separate packages (`phortugol/laravel-plugin`, `phortugol/swoole-plugin`).

## Package Ecosystem

```
phortugol/phortugol        ← this repo (core)
phortugol/laravel-plugin   ← Service Provider, FiberRuntime, Artisan commands
phortugol/swoole-plugin    ← SwooleRuntime
```

## Architecture

The interpreter pipeline has three stages:

```
Source code (string)
    ↓
Tokenizer        Phortugol\Lexer\Tokenizer
    ↓
Parser           Phortugol\Parser\Parser
    ↓             produces an AST (node classes in Phortugol\Parser\Nodes\)
Runner           Phortugol\Interpreter\Runner
    ↓             depends on a Runtime (Strategy Pattern)
Output / Input
```

### Runtime — the core abstraction

`Phortugol\Contracts\Runtime` is the single interface the `Runner` depends on. It defines how output is written and how input is read. This is a **Strategy Pattern** — the Runner knows nothing about Fibers, WebSockets, terminals, or Laravel.

```
Contracts\Runtime (interface)
├── TerminalRuntime   — ships with core, uses echo + fgets(STDIN)
├── FakeRuntime       — ships with core, used in tests
├── FiberRuntime      — lives in laravel-plugin
└── SwooleRuntime     — lives in swoole-plugin
```

### ExecutorDispatcher — how the Runner executes nodes

The `Runner` never contains a `match` or `instanceof` check. Instead it delegates to an `ExecutorDispatcher`, which maps each Node type to a dedicated `NodeExecutor`.

```
Runner::execute(Node $node)
    ↓
ExecutorDispatcher::dispatch(Node $node, Runner $runner)
    ↓
IfExecutor::execute(Node $node, Runner $runner)
```

Each executor lives in its own file. Adding a new Portugol construct = creating a new executor + registering it in `ExecutorDispatcher::default()`. The `Runner` is never touched.

```
Interpreter/
  Runner.php
  Environment.php
  ExecutorDispatcher.php
  Executors/
    IfExecutor.php
    WhileExecutor.php
    ForExecutor.php
    WriteExecutor.php
    ReadExecutor.php
    AssignExecutor.php
```

`Runner::create(Runtime $runtime)` is the canonical factory — it builds the Runner with `ExecutorDispatcher::default()`. Pass a custom dispatcher only when testing or extending.

### Namespace map

```
Phortugol\Contracts\Node                   ← marker interface for all AST nodes
Phortugol\Contracts\NodeExecutor           ← interface for node executors
Phortugol\Contracts\Runtime               ← interface for I/O strategy

Phortugol\Concerns\                        ← shared traits (e.g. HasCoercion)

Phortugol\Lexer\Tokenizer
Phortugol\Lexer\Token
Phortugol\Lexer\TokenType

Phortugol\Parser\Parser
Phortugol\Parser\Nodes\                    ← concrete AST node classes (implement Contracts\Node)

Phortugol\Interpreter\Runner
Phortugol\Interpreter\Environment
Phortugol\Interpreter\ExecutorDispatcher
Phortugol\Interpreter\Executors\           ← one class per Node type (implement Contracts\NodeExecutor)

Phortugol\Runtime\TerminalRuntime          ← implements Contracts\Runtime
Phortugol\Runtime\FakeRuntime              ← implements Contracts\Runtime

Phortugol\Exceptions\LexerException
Phortugol\Exceptions\ParseException
Phortugol\Exceptions\RuntimeException
```

## Commands

```bash
# Install dependencies
ddev exec composer install

# Run tests
ddev exec composer test

# Run tests with coverage
ddev exec composer test:coverage

# Static analysis
ddev exec composer analyse

# Format code
ddev exec composer format

# Format check only (CI)
ddev exec composer format:check
```

## Key Decisions

- **No Laravel in core** — never import anything from `Illuminate\` inside `src/`
- **`final readonly class` everywhere possible** — especially AST nodes and Token
- **No `eval()`** — the interpreter walks the AST manually
- **No `match`/`instanceof` in Runner** — use `ExecutorDispatcher::dispatch()` instead
- **Loop guard** — all loops have a 100,000 iteration guard to prevent infinite loops
- **Exceptions are typed** — always throw `LexerException`, `ParseException`, or `RuntimeException`, never the base `\Exception`
- **`Runner::create(Runtime)`** — canonical factory, builds Runner with `ExecutorDispatcher::default()`

@CONTRIBUTING.md
