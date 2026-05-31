---
description: >
  Add a new token type to the Phortugol lexer. Use when the user wants to support
  a new keyword, operator, or symbol in the Portugol language. Covers TokenType enum,
  Tokenizer recognition, and the corresponding test.
---

# Add Token Type

Follow these steps in order. Do not skip any step.

## Step 1 — Add to the TokenType enum

Open `src/Lexer/TokenType.php` and add the new case to the appropriate group
(Keywords, Operators, or Symbols). Keep the groups sorted alphabetically within each group.

```php
case NEW_TOKEN = 'NEW_TOKEN';
```

## Step 2 — Teach the Tokenizer to recognize it

Open `src/Lexer/Tokenizer.php`.

- **If it is a keyword**: add it to the `KEYWORDS` constant array.
  ```php
  'novapalavra' => TokenType::NEW_TOKEN,
  ```
- **If it is a single-character operator or symbol**: add a match arm in `tokenize()`.
  ```php
  $this->current === 'x' => $this->consume1(TokenType::NEW_TOKEN),
  ```
- **If it is a two-character operator** (e.g. `<>`): add a match arm that checks `peek()` first.
  ```php
  $this->current === '<' && $this->peek() === 'x' => $this->consume2(TokenType::NEW_TOKEN),
  ```

## Step 3 — Write the test

Create or open `tests/Unit/Lexer/TokenizerTest.php` and add a test case:

```php
it('tokenizes <token name>', function (): void {
    $tokens = (new Tokenizer('<source snippet>'))->tokenize();

    expect($tokens[0]->type)->toBe(TokenType::NEW_TOKEN)
        ->and($tokens[0]->value)->toBe('<expected value>');
});
```

Also add an edge case test if the token can appear in context (e.g. inside an expression).

## Step 4 — Run the suite

```bash
composer test -- --filter TokenizerTest
```

All tests must pass before finishing.
