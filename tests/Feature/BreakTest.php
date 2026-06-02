<?php

declare(strict_types = 1);

use Phortugol\Interpreter\Runner;
use Phortugol\Runtime\FakeRuntime;

it('breaks out of enquanto loop', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        i <- 1
        enquanto verdadeiro faca
          escreva i
          i <- i + 1
          se i > 3 entao
            interrompa
          fimse
        fimenquanto
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['1', '2', '3']);
});

it('breaks out of para loop', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        para i de 1 ate 10 faca
          se i > 3 entao
            interrompa
          fimse
          escreva i
        fimpara
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['1', '2', '3']);
});

it('breaks out of repita loop', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        i <- 1
        repita
          se i > 3 entao
            interrompa
          fimse
          escreva i
          i <- i + 1
        ate falso
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['1', '2', '3']);
});
