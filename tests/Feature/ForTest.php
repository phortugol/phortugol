<?php

declare(strict_types = 1);

use Phortugol\Exceptions\RuntimeException;
use Phortugol\Interpreter\Runner;
use Phortugol\Runtime\FakeRuntime;

it('executes para/fimpara counting up', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        para i de 1 ate 3 faca
          escreva i
        fimpara
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['1', '2', '3']);
});

it('skips body when range is empty', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        para i de 5 ate 1 faca
          escreva i
        fimpara
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe([]);
});

it('executes para/fimpara with passo', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        para i de 0 ate 6 passo 2 faca
          escreva i
        fimpara
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['0', '2', '4', '6']);
});

it('executes para/fimpara counting down with negative passo', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        para i de 3 ate 1 passo -1 faca
          escreva i
        fimpara
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['3', '2', '1']);
});

it('accumulates sum in para loop', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        soma <- 0
        para i de 1 ate 5 faca
          soma <- soma + i
        fimpara
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
        para i de 1 ate 200001 faca
          escreva i
        fimpara
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();

    expect(fn () => Runner::create($runtime)->run($source))
        ->toThrow(RuntimeException::class, 'Loop exceeded 100,000 iterations');
});
