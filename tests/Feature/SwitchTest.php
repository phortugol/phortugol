<?php

declare(strict_types = 1);

use Phortugol\Interpreter\Runner;
use Phortugol\Runtime\FakeRuntime;

it('executes matching caso', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        x <- 2
        seja x
          caso 1
            escreva "um"
          caso 2
            escreva "dois"
          caso 3
            escreva "tres"
        fimcaso
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['dois']);
});

it('executes outrocaso when no caso matches', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        x <- 9
        seja x
          caso 1
            escreva "um"
          caso 2
            escreva "dois"
          outrocaso
            escreva "outro"
        fimcaso
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['outro']);
});

it('executes nothing when no caso matches and no outrocaso', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        x <- 9
        seja x
          caso 1
            escreva "um"
        fimcaso
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe([]);
});

it('stops at first matching caso and does not fall through', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        x <- 1
        seja x
          caso 1
            escreva "um"
          caso 1
            escreva "duplicado"
        fimcaso
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['um']);
});

it('matches string values', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        s <- "ola"
        seja s
          caso "ola"
            escreva "hello"
          caso "tchau"
            escreva "bye"
        fimcaso
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime();
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['hello']);
});
