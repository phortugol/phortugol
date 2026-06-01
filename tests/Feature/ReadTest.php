<?php

declare(strict_types = 1);

use Phortugol\Interpreter\Runner;
use Phortugol\Runtime\FakeRuntime;

it('reads a string value into a variable', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        leia nome
        escreva nome
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime(['Alice']);
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['Alice']);
});

it('reads an integer value and coerces it', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        leia x
        escreva x + 1
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime(['5']);
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['6']);
});

it('reads multiple variables', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        inicio
        leia x, y
        escreva x + y
        fimalgoritmo
        PORTUGOL;

    $runtime = new FakeRuntime(['3', '4']);
    Runner::create($runtime)->run($source);

    expect($runtime->output)->toBe(['7']);
});
