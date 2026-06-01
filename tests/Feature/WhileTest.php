<?php

declare(strict_types = 1);

use Phortugol\Exceptions\RuntimeException;
use Phortugol\Interpreter\Runner;
use Phortugol\Runtime\FakeRuntime;

it('skips body when condition starts false', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        enquanto falso faca
          escreva "never"
        fimenquanto
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe([]);
});

it('executes body while condition is true', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        i <- 1
        enquanto i <= 3 faca
          escreva i
          i <- i + 1
        fimenquanto
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['1', '2', '3']);
});

it('accumulates values in a loop', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        soma <- 0
        i <- 1
        enquanto i <= 5 faca
          soma <- soma + i
          i <- i + 1
        fimenquanto
        escreva soma
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['15']);
});

it('throws RuntimeException when loop guard is exceeded', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        enquanto verdadeiro faca
          escreva "x"
        fimenquanto
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();

    expect(fn () => Runner::create($runtime)->run($source))
        ->toThrow(RuntimeException::class, 'Loop exceeded 100,000 iterations');
});
