# Phortugol

[![PHP Version](https://img.shields.io/badge/php-%5E8.5-blue)](https://www.php.net)
[![License: MIT](https://img.shields.io/badge/license-MIT-green)](LICENSE)
[![PHPStan Level](https://img.shields.io/badge/phpstan-level%208-blueviolet)](https://phpstan.org)
[![Tests](https://img.shields.io/badge/tests-pest-orange)](https://pestphp.com)

A Portugol interpreter written in PHP — the educational pseudocode language used in [VisuAlg](https://sourceforge.net/projects/visualg30/) and Brazilian CS classrooms.

> **Phortugol** = **PH**P + Por**tugol**

---

## What is Portugol?

Portugol is a Portuguese pseudocode language widely used in Brazilian computer science education. It reads like natural language, making it ideal for teaching programming fundamentals without the syntax overhead of a production language.

```portugol
algoritmo "hello"
inicio
  escreva "Olá, mundo!"
fimalgoritmo
```

This package tokenizes, parses, and executes Portugol programs entirely in PHP — no `eval()`, no shell calls. Just a clean AST-walking interpreter.

---

## Requirements

- PHP 8.5+

---

## Installation

```bash
composer require phortugol/phortugol
```

---

## Usage

### Running a program

```php
use Phortugol\Interpreter\Runner;
use Phortugol\Runtime\TerminalRuntime;

$source = <<<'PORTUGOL'
    algoritmo "fatorial"
    inicio
    n <- 5
    fat <- 1
    enquanto n > 1 faca
      fat <- fat * n
      n <- n - 1
    fimenquanto
    escreva fat
    fimalgoritmo
    PORTUGOL;

Runner::create(new TerminalRuntime())->run($source);
// Output: 120
```

### Capturing output (no terminal required)

Use `FakeRuntime` to run programs in a controlled environment — great for web apps, APIs, and tests:

```php
use Phortugol\Interpreter\Runner;
use Phortugol\Runtime\FakeRuntime;

$runtime = new FakeRuntime(inputs: ['Alice']);

Runner::create($runtime)->run(<<<'PORTUGOL'
    algoritmo "saudacao"
    inicio
    leia nome
    escreva "Olá, ", nome, "!"
    fimalgoritmo
    PORTUGOL);

$runtime->output; // ['Olá, Alice!']
```

### Handling errors

```php
use Phortugol\Exceptions\LexerException;
use Phortugol\Exceptions\ParseException;
use Phortugol\Exceptions\RuntimeException;

try {
    Runner::create($runtime)->run($source);
} catch (LexerException $e) {
    // Unexpected character in source
} catch (ParseException $e) {
    // Syntax error
} catch (RuntimeException $e) {
    // Runtime error (e.g., division by zero, loop guard exceeded)
}
```

---

## Language Reference

### Program structure

```portugol
algoritmo "nome"
var
  x: inteiro
  nome: caractere
inicio
  // seu código aqui
fimalgoritmo
```

### Data types

| Keyword      | PHP equivalent | Example          |
|--------------|----------------|------------------|
| `inteiro`    | `int`          | `x <- 42`        |
| `real`       | `float`        | `pi <- 3.14`     |
| `caractere`  | `string`       | `s <- "texto"`   |
| `logico`     | `bool`         | `ok <- verdadeiro` |

### Operators

| Category     | Operators                             |
|--------------|---------------------------------------|
| Arithmetic   | `+` `-` `*` `/` `div` `mod`           |
| Comparison   | `=` `<>` `<` `>` `<=` `>=`           |
| Logical      | `e` `ou` `nao`                        |
| Assignment   | `<-` `:=`                             |

### Supported constructs

| Construct    | Syntax                                         |
|--------------|------------------------------------------------|
| Output       | `escreva expr, expr` / `escreval expr`         |
| Input        | `leia variavel, variavel`                      |
| Assignment   | `variavel <- expressao`                        |
| Conditional  | `se cond entao ... [senao ...] fimse`          |
| While loop   | `enquanto cond faca ... fimenquanto`           |

### Examples

**Conditional:**
```portugol
se x > 10 entao
  escreva "grande"
senao
  escreva "pequeno"
fimse
```

**While loop:**
```portugol
i <- 1
enquanto i <= 10 faca
  escreva i
  i <- i + 1
fimenquanto
```

**Reading multiple inputs:**
```portugol
leia a, b
escreva a + b
```

---

## Custom Runtime

The `Runner` depends on the `Runtime` interface — a two-method contract for I/O:

```php
interface Runtime
{
    public function write(string $text): void;
    public function read(): string;
}
```

Implement it to integrate Phortugol with any I/O layer — WebSockets, queues, HTTP streams, or anything else:

```php
use Phortugol\Contracts\Runtime;

final class MyRuntime implements Runtime
{
    public function write(string $text): void
    {
        // push to a websocket, queue, response stream...
    }

    public function read(): string
    {
        // pull from a request body, queue message, fiber yield...
    }
}

Runner::create(new MyRuntime())->run($source);
```

First-party integrations for Laravel (Fiber-based) and Swoole live in separate packages:

| Package | Description |
|---------|-------------|
| [`phortugol/laravel-plugin`](https://github.com/phortugol/laravel-plugin) | `FiberRuntime`, Service Provider, Artisan commands |
| [`phortugol/swoole-plugin`](https://github.com/phortugol/swoole-plugin) | `SwooleRuntime` for async execution |

---

## Architecture

The interpreter is a three-stage pipeline:

```
Source string
    ↓  Tokenizer      → token stream
    ↓  Parser         → AST (nodes in Phortugol\Parser\Nodes\)
    ↓  Runner         → execution via ExecutorDispatcher
Output / Input
```

Each AST node type has a dedicated `NodeExecutor`. The `Runner` never uses `instanceof` or `match` — it delegates entirely to `ExecutorDispatcher::dispatch()`. Adding a new language construct means creating one executor and registering it; the `Runner` is never touched.

---

## Safety

- **No `eval()`** — the interpreter walks the AST manually
- **Loop guard** — all loops throw `RuntimeException` after 100,000 iterations
- **Typed exceptions** — `LexerException`, `ParseException`, `RuntimeException`; never the base `\Exception`

---

## Development

```bash
git clone git@github.com:phortugol/phortugol.git
cd phortugol
composer install
```

```bash
composer test            # run the test suite (Pest)
composer test:coverage   # test suite with coverage report
composer analyse         # PHPStan level 8
composer format          # fix code style (Pint PSR-12)
composer check           # format:check + analyse + test
```

---

## Testing

Tests use [Pest](https://pestphp.com). Feature tests run full Portugol programs end-to-end against `FakeRuntime`:

```php
it('accumulates a sum in a while loop', function (): void {
    $runtime = new FakeRuntime();

    Runner::create($runtime)->run(<<<'PORTUGOL'
        algoritmo "soma"
        inicio
        soma <- 0
        i <- 1
        enquanto i <= 5 faca
          soma <- soma + i
          i <- i + 1
        fimenquanto
        escreva soma
        fimalgoritmo
        PORTUGOL);

    expect($runtime->output)->toBe(['15']);
});
```

---

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for commit conventions, PR guidelines, and setup instructions.

---

## License

MIT — see [LICENSE](LICENSE).
