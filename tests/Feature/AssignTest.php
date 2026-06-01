<?php

declare(strict_types = 1);

use Phortugol\Interpreter\Runner;
use Phortugol\Runtime\FakeRuntime;

it('assigns integer and reads it back', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        x <- 10
        escreva x
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['10']);
});

it('assigns result of arithmetic expression', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        x <- 3 + 4
        escreva x
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['7']);
});

it('reassigns variable', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        x <- 1
        x <- 2
        escreva x
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['2']);
});

it('assigns variable from another variable', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        x <- 7
        y <- x
        escreva y
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['7']);
});

it('supports := assignment operator', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        x := 99
        escreva x
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['99']);
});
