# Architecture Rules

## Contracts — the single source of truth for interfaces

All interfaces live in `src/Contracts/` under `Phortugol\Contracts`:
- `Phortugol\Contracts\Node` — marker interface; every AST node class implements this
- `Phortugol\Contracts\NodeExecutor` — interface for executor classes
- `Phortugol\Contracts\Runtime` — interface for I/O strategy (write/read)

Concrete implementations live in their own modules (`Parser\Nodes\`, `Interpreter\Executors\`, `Runtime\`).
Never place an interface outside `Contracts\` — follow the Pest/Saloon pattern.

## Concerns — horizontal decomposition via traits

Shared traits live in `src/Concerns/` under `Phortugol\Concerns`.

The goal is **organizational clarity**, not just avoiding duplication. A trait is justified whenever a class has more than one identifiable responsibility — even if no other class uses that trait yet.

This is inspired by how Filament and Laravel organize their internals: each trait encapsulates one concern, and the class becomes a thin composition of those concerns.

### Naming conventions

| Prefix | Meaning | Example |
|---|---|---|
| `Has` | the class possesses a set of related methods | `HasCoercion` |
| *(none)* | a named set of operations belonging to a domain | `BinaryOperations` |

Avoid `Can` (implies optional capability) and `As` (implies a role/adapter pattern).

### Rules

- A class with more than one responsibility is a candidate for trait extraction
- Traits may `use` other traits to compose behavior (e.g. `BinaryOperations` uses `HasCoercion`)
- Traits must never depend on concrete classes — only on PHP primitives and project exceptions
- Never place a trait outside `Concerns\`

## Strict Boundaries

- `src/` must never import from `Illuminate\`, `Symfony\`, or any framework namespace
- `src/` dependencies are: PHP 8.5 stdlib only
- If a class needs Laravel, it belongs in `phortugol/laravel-plugin`, not here

## Class Design

- Use `final readonly class` for all value objects and AST nodes
- Use `final class` for services (Tokenizer, Parser, Runner, Runtimes)
- Interfaces use no prefix/suffix — `Runtime`, not `RuntimeInterface` or `IRuntime`
- Exceptions extend `\RuntimeException` or `\InvalidArgumentException` — never the base `\Exception`

## The ExecutorDispatcher

The `Runner` never uses `match` or `instanceof` to decide how to execute a node.
It delegates entirely to `ExecutorDispatcher::dispatch(Node $node, Runner $runner)`.

Each Node type has one dedicated `NodeExecutor` in `src/Interpreter/Executors/`.
To add a new construct: create the executor, register it in `ExecutorDispatcher::default()`.
Never touch `Runner` for this.

`Phortugol\Contracts\NodeExecutor` is an interface:
```php
interface NodeExecutor
{
    public function execute(Node $node, Runner $runner): void;
}
```

Executors call `$runner->execute($childNode)` for recursive execution of child nodes.
Executors must never instantiate other executors directly.

## The Runtime Contract

The `Runner` receives a `Runtime` via constructor injection. It must never:
- Instantiate a `Runtime` itself
- Check `instanceof` to branch behavior
- Know about Fibers, WebSockets, or any I/O mechanism

## AST Nodes

All AST node classes:
- Live in `Phortugol\Parser\Nodes\`
- Are `final readonly class`
- Implement the `Phortugol\Contracts\Node` marker interface
- Have no methods — only constructor promoted properties

## Environment (variable scope)

`Phortugol\Interpreter\Environment` manages variable state during execution.
It is created fresh by the `Runner` on each `run()` call — never reused across runs.

## Loop Safety

Every loop executor must increment a guard counter.
Throw `RuntimeException` if iterations exceed 100,000.
