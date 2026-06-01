<?php

declare(strict_types = 1);

use Phortugol\Interpreter\Runner;
use Phortugol\Runtime\FakeRuntime;

it('executes escreva without newline', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        escreva "hello"
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['hello']);
});

it('executes escreval with newline', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        escreval "hello"
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(["hello\n"]);
});

it('executes escreva with multiple expressions', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        escreva "hello", " ", "world"
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['hello world']);
});

it('executes escreva with integer literal', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        escreva 42
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['42']);
});

it('executes escreva with boolean verdadeiro', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        escreva verdadeiro
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['verdadeiro']);
});

it('executes escreva with boolean falso', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        escreva falso
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['falso']);
});

it('executes multiple escreva statements', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        escreva "a"
        escreva "b"
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['a', 'b']);
});
