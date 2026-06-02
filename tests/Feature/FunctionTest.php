<?php

declare(strict_types = 1);

use Phortugol\Exceptions\RuntimeException;
use Phortugol\Interpreter\Runner;
use Phortugol\Runtime\FakeRuntime;

it('calls a function and uses its return value', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        funcao dobrar(x: inteiro): inteiro
          retorne x * 2
        fimfuncao
        inicio
        escreva dobrar(5)
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['10']);
});

it('assigns function return value to a variable', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        funcao quadrado(n: inteiro): inteiro
          retorne n * n
        fimfuncao
        inicio
        resultado <- quadrado(4)
        escreva resultado
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['16']);
});

it('uses function return value in an expression', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        funcao soma(a: inteiro, b: inteiro): inteiro
          retorne a + b
        fimfuncao
        inicio
        escreva soma(3, 4) + soma(1, 2)
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['10']);
});

it('function parameters do not affect outer scope', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        funcao identidade(x: inteiro): inteiro
          x <- 99
          retorne x
        fimfuncao
        inicio
        x <- 1
        identidade(x)
        escreva x
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['1']);
});

it('function can access global variables', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        funcao getBase(): inteiro
          retorne base
        fimfuncao
        inicio
        base <- 10
        escreva getBase()
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['10']);
});

it('throws RuntimeException on wrong argument count for function', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        funcao foo(a: inteiro): inteiro
          retorne a
        fimfuncao
        inicio
        foo(1, 2)
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();

    expect(fn () => Runner::create($runtime)->run($source))
        ->toThrow(RuntimeException::class, "'foo' expects 1 argument(s), 2 given");
});
