---
paths:
  - "src/**/*.php"
  - "tests/**/*.php"
---

# PHP Style Rules

## Language Version

Target PHP 8.5. Use modern features freely:
- Named arguments
- Match expressions over switch
- Nullsafe operator
- Fibers (only in laravel-plugin, never in core)
- Property promotion
- Readonly properties
- Enums (TokenType is an enum)
- `new Foo()->method()` — no outer parentheses needed (PHP 8.4+); never write `(new Foo())->method()`

## Class Modifiers

- Prefer `final readonly class` for data classes and AST nodes
- Prefer `final class` for services
- Never use `abstract class` — use interfaces instead
- No `public` properties unless the class is `readonly`

## SOLID

- Single Responsibility: one class, one reason to change
- Open/Closed: extend via interface, never modify the Runner to add I/O behavior
- Liskov: all `Runtime` implementations must be interchangeable
- Interface Segregation: keep interfaces small (`Runtime` has only 2 methods)
- Dependency Inversion: `Runner` depends on `Runtime` abstraction, not concretions

## Object Calisthenics

- One level of indentation per method when possible
- No `else` after `return`
- Wrap primitives in value objects where it adds meaning
- No getters/setters — use constructor promotion and readonly
- Small classes, small methods

## Formatting

- PSR-12 standard (enforced by Pint)
- Run `composer format` before committing
- Strict types declared with spaces: `declare(strict_types = 1);` (enforced by Pint)
- Union types with spaces: `string | int | null` not `string|int|null` (enforced by Pint)
- Never use `?Type` shorthand — always write the full union with `null` last: `array | null` not `?array`
- Order union types from most complex to simplest, `null` always last: `array | string | int | float | bool | null`
- Space after negation: `! $var` not `!$var` (enforced by Pint)
- Blank line between class properties (enforced by Pint)
- Constructors always use multiline parameter format, even with a single parameter — trailing comma required (not enforced by Pint, apply manually):
  ```php
  // correct
  public function __construct(
      private readonly string $source,
  ) {
  }

  // wrong
  public function __construct(private readonly string $source)
  {
  }
  ```

## PHPDoc

- Omit `@param` and `@return` when types are already in the signature
- Use `@param` only for generics: `@param array<int, Token> $tokens`
- Never write prose PHPDoc for obvious methods
