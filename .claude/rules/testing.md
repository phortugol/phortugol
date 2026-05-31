---
paths:
  - "tests/**/*.php"
---

# Testing Rules

## Framework

Pest PHP. Never use PHPUnit syntax directly (no `$this->assert*()`).
Use Pest's expectation API: `expect($value)->toBe(...)`.

## Structure

```
tests/
  Unit/
    Lexer/       ← tests for Tokenizer
    Parser/      ← tests for Parser
    Interpreter/ ← tests for Runner
  Feature/       ← end-to-end: source string → output array
```

## FakeRuntime

Always use `Phortugol\Runtime\FakeRuntime` in tests — never `TerminalRuntime`.

```php
$runtime = new FakeRuntime(inputs: ['João', '25']);
$runner  = Runner::create($runtime);
$runner->run($ast);

expect($runtime->output)->toBe(['Olá, João']);
```

## Naming

Test descriptions are plain English sentences:
- `it('tokenizes integer literals')`
- `it('throws LexerException on unexpected character')`
- `it('executes a while loop with a guard')`

## What to Test

- Tokenizer: each token type, edge cases, error cases
- Parser: valid programs produce correct AST shape, invalid programs throw ParseException
- Runner: each statement type, expressions, loop guard, runtime errors
- Feature: full Portugol programs produce expected output

## No Framework in Tests

Tests must not bootstrap Laravel or any framework.
`composer test` runs in a plain PHP environment.
