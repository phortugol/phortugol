<?php

declare(strict_types = 1);

use Phortugol\Exceptions\RuntimeException;
use Phortugol\Interpreter\Runner;
use Phortugol\Runtime\FakeRuntime;

it('executes body at least once when condition starts true', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        repita
          escreva "once"
        ate verdadeiro
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['once']);
});

it('repeats until condition becomes true', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        i <- 1
        repita
          escreva i
          i <- i + 1
        ate i > 3
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['1', '2', '3']);
});

it('accumulates sum in repita loop', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        soma <- 0
        i <- 1
        repita
          soma <- soma + i
          i <- i + 1
        ate i > 5
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe([]);
});

it('throws RuntimeException when loop guard is exceeded', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        repita
          escreva "x"
        ate falso
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();

    expect(fn () => Runner::create($runtime)->run($source))
        ->toThrow(RuntimeException::class, 'Loop exceeded 100,000 iterations');
});
