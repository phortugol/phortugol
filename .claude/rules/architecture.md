# Architecture Rules

## This is a Package, not an Application

Phortugol is a **composer package**. There are no "services" in the application sense — no service containers, no service classes, no singleton lifecycles. Every class is either a value object, an AST node, an executor, a runtime, or a parser component. The traditional "service vs. value object" distinction does not apply here.

## Class Design

- Use `final readonly class` everywhere possible — for nodes, token, executors, parsers, runtimes, and all other classes
- The only reason to drop `readonly` is when a class truly needs mutable state (e.g., `Environment`, `Runner` with internal iteration state)
- Interfaces use no prefix/suffix — `Runtime`, not `RuntimeInterface` or `IRuntime`
- Exceptions extend `\RuntimeException` or `\InvalidArgumentException` — never the base `\Exception`

## Contracts — namespaced to mirror their module

Interfaces live in `src/Contracts/` under `Phortugol\Contracts`, **mirroring the module structure** of their implementations:

```
Phortugol\Contracts\Node                      ← marker interface for all AST nodes
Phortugol\Contracts\Runtime                   ← I/O strategy interface
Phortugol\Contracts\Parser\StatementParser    ← interface for statement parsers
Phortugol\Contracts\Interpreter\NodeExecutor  ← interface for executors
```

Base-level contracts (`Contracts\Node`, `Contracts\Runtime`) are allowed when they look beautiful on import and have no natural sub-namespace. Sub-namespaced contracts (`Contracts\Parser\`, `Contracts\Interpreter\`) are preferred when grouping makes the import readable and intentional.

Never place an interface outside `Contracts\`.

## Concerns — code as craft

Traits live in `src/Concerns/` under `Phortugol\Concerns`, **mirroring the module structure** of the classes that use them:

```
Phortugol\Concerns\Parser\Expression\ArithmeticPrecedence
Phortugol\Concerns\Parser\Expression\PrecedenceHierarchy
Phortugol\Concerns\Interpreter\HasCoercion
Phortugol\Concerns\Interpreter\BinaryOperations
```

### The purpose of traits here is beauty, not utility

Traits exist to make code **elegant, readable, and well-named** — not merely to avoid duplication. A trait is justified when:

- A class grows beyond one screen and splitting it into named concerns makes it more beautiful to read
- A name exists that perfectly describes a set of related methods
- The class becomes a thin, expressive composition of well-named traits

Even if no other class ever uses a trait, it is still worthwhile if it makes the composing class lovelier. The ideal class body is a list of `use` statements — nothing else.

### Traits composing traits

Traits may (and should) `use` other traits to build layered, composable behavior:

```php
trait BinaryOperations
{
    use HasCoercion;
    // ...
}
```

This is the preferred pattern for building depth: fine-grained traits composed into broader ones, composed into the final class.

### Naming conventions

| Prefix | Meaning | Example |
|---|---|---|
| `Has` | the class possesses a set of related methods | `HasCoercion` |
| *(none)* | a named set of operations belonging to a domain | `BinaryOperations`, `ArithmeticPrecedence` |

Avoid `Can` (implies optional capability) and `As` (implies a role/adapter pattern).

Names must be **clear and beautiful to read** — a name that requires a comment to explain is not a good name.

### Rules

- Never place a trait outside `Concerns\`
- Traits may depend on other traits and on project-internal types (e.g., `TokenStream`, `Node`) — the package is self-contained, so internal coupling inside `src/` is acceptable
- Traits must never import from `Illuminate\`, `Symfony\`, or any framework namespace

## Parser — no `parse*` methods allowed

The `Parser` class must never contain methods prefixed with `parse`. The class name already implies parsing — a method called `parseProcedure()` inside `Parser` is redundant by definition.

Every parsing concern must be extracted to a dedicated class:

- **Statements** (`Parser\Statements\`) — constructs that appear inside the main block (after `inicio`). Implement `Contracts\Parser\Statement` (`__invoke(Parser $parser): Node`).
- **Declarations** (`Parser\Declarations\`) — constructs that appear before `inicio` (subroutines). Implement `Contracts\Parser\Declaration` (`__invoke(Parser $parser): Node`).
- **Section parsers** (e.g., `Parser\VarSection`) — sections of the program structure that return a list of nodes. Receive their dependency via constructor and expose a `parse()` method, mirroring the `Expression` pattern.

Even when the gain feels minimal, always create the class. Keeping logic inside `Parser` as private methods is not allowed.

## Strict Boundaries

- `src/` must never import from `Illuminate\`, `Symfony\`, or any framework namespace
- `src/` dependencies are: PHP 8.5 stdlib only
- If a class needs Laravel, it belongs in `phortugol/laravel-plugin`, not here

## The ExecutorDispatcher

The `Runner` never uses `match` or `instanceof` to decide how to execute a node.
It delegates entirely to `ExecutorDispatcher::dispatch(Node $node, Runner $runner)`.

Each Node type has one dedicated `NodeExecutor` in `src/Interpreter/Executors/`.
To add a new construct: create the executor, register it in `ExecutorDispatcher::default()`.
Never touch `Runner` for this.

`Phortugol\Contracts\Interpreter\NodeExecutor` is an interface:
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
