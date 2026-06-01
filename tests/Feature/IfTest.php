<?php

declare(strict_types = 1);

use Phortugol\Interpreter\Runner;
use Phortugol\Runtime\FakeRuntime;

it('executes then branch when condition is true', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        se verdadeiro entao
          escreva "yes"
        fimse
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['yes']);
});

it('skips then branch when condition is false', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        se falso entao
          escreva "yes"
        fimse
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe([]);
});

it('executes else branch when condition is false', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        se falso entao
          escreva "yes"
        senao
          escreva "no"
        fimse
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['no']);
});

it('evaluates comparison in condition', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        x <- 5
        se x > 3 entao
          escreva "big"
        fimse
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['big']);
});

it('evaluates equality in condition', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        x <- 10
        se x = 10 entao
          escreva "equal"
        senao
          escreva "not equal"
        fimse
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['equal']);
});
