<?php

declare(strict_types = 1);

use Phortugol\Exceptions\RuntimeException;
use Phortugol\Interpreter\Runner;
use Phortugol\Runtime\FakeRuntime;

it('declares and reads a vector element', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        var
          v: vetor[1..5] de inteiro
        inicio
        v[1] <- 42
        escreva v[1]
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['42']);
});

it('assigns and reads multiple vector positions', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        var
          v: vetor[1..3] de inteiro
        inicio
        v[1] <- 10
        v[2] <- 20
        v[3] <- 30
        escreva v[1]
        escreva v[2]
        escreva v[3]
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['10', '20', '30']);
});

it('uses variable as index', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        var
          v: vetor[1..5] de inteiro
        inicio
        i <- 3
        v[i] <- 99
        escreva v[i]
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['99']);
});

it('fills and reads vector in a loop', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        var
          v: vetor[1..3] de inteiro
        inicio
        para i de 1 ate 3 faca
          v[i] <- i * 10
        fimpara
        para i de 1 ate 3 faca
          escreva v[i]
        fimpara
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['10', '20', '30']);
});

it('supports zero-based index', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        var
          v: vetor[0..2] de inteiro
        inicio
        v[0] <- 1
        v[1] <- 2
        v[2] <- 3
        escreva v[0]
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['1']);
});

it('throws RuntimeException on out-of-bounds access', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        var
          v: vetor[1..3] de inteiro
        inicio
        escreva v[10]
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();

    expect(fn () => Runner::create($runtime)->run($source))
        ->toThrow(RuntimeException::class, 'Index 10 out of bounds');
});

it('throws RuntimeException on out-of-bounds assignment', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        var
          v: vetor[1..3] de inteiro
        inicio
        v[10] <- 42
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();

    expect(fn () => Runner::create($runtime)->run($source))
        ->toThrow(RuntimeException::class, 'Index 10 out of bounds');
});
