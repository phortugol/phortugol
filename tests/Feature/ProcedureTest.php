<?php

declare(strict_types = 1);

use Phortugol\Exceptions\RuntimeException;
use Phortugol\Interpreter\Runner;
use Phortugol\Runtime\FakeRuntime;

it('calls a procedure without parameters', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        procedimento saudar()
          escreva "ola"
        fimprocedimento
        inicio
        saudar()
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['ola']);
});

it('calls a procedure with parameters', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        procedimento dobrar(x: inteiro)
          escreva x * 2
        fimprocedimento
        inicio
        dobrar(5)
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['10']);
});

it('calls a procedure multiple times', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        procedimento imprimir(n: inteiro)
          escreva n
        fimprocedimento
        inicio
        imprimir(1)
        imprimir(2)
        imprimir(3)
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['1', '2', '3']);
});

it('procedure parameters are local and do not affect outer scope', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        procedimento testar(x: inteiro)
          x <- 99
        fimprocedimento
        inicio
        x <- 1
        testar(x)
        escreva x
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['1']);
});

it('procedure can access and modify global variables', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        procedimento incrementar()
          contador <- contador + 1
        fimprocedimento
        inicio
        contador <- 0
        incrementar()
        incrementar()
        escreva contador
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['2']);
});

it('throws RuntimeException when calling undefined subroutine', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        inexistente()
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();

    expect(fn () => Runner::create($runtime)->run($source))
        ->toThrow(RuntimeException::class);
});

it('throws RuntimeException on wrong argument count', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        procedimento foo(a: inteiro)
          escreva a
        fimprocedimento
        inicio
        foo(1, 2)
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();

    expect(fn () => Runner::create($runtime)->run($source))
        ->toThrow(RuntimeException::class, "'foo' expects 1 argument(s), 2 given");
});
