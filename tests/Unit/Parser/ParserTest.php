<?php

declare(strict_types = 1);

use Phortugol\Enums\TokenType;
use Phortugol\Lexer\Tokenizer;
use Phortugol\Parser\Nodes\ProgramNode;
use Phortugol\Parser\Parser;
use Phortugol\Support\Parser\Nodes;

function parseSource(string $body): ProgramNode
{
    $source = <<<PORTUGOL
        algoritmo "teste"
        inicio
        {$body}
        fimalgoritmo
        PORTUGOL;

    return new Parser(new Tokenizer($source)->tokenize())->parse();
}

it('parses escreva as WriteNode without newline', function (): void {
    $ast = parseSource('escreva "hello"');

    expect($ast->statements[0])->toEqual(Nodes::write([Nodes::literal('hello')]));
});

it('parses escreval as WriteNode with newline', function (): void {
    $ast = parseSource('escreval "hello"');

    expect($ast->statements[0])->toEqual(Nodes::writeln([Nodes::literal('hello')]));
});

it('parses escreva with parentheses', function (): void {
    $ast = parseSource('escreva("hello")');

    expect($ast->statements[0])->toEqual(Nodes::write([Nodes::literal('hello')]));
});

it('parses escreva with multiple expressions', function (): void {
    $ast = parseSource('escreva "a", "b", "c"');

    expect($ast->statements[0])->toEqual(
        Nodes::write([Nodes::literal('a'), Nodes::literal('b'), Nodes::literal('c')]),
    );
});

it('parses leia as ReadNode', function (): void {
    $ast = parseSource('leia x');

    expect($ast->statements[0])->toEqual(Nodes::read(['x']));
});

it('parses leia with multiple variables', function (): void {
    $ast = parseSource('leia x, y, z');

    expect($ast->statements[0])->toEqual(Nodes::read(['x', 'y', 'z']));
});

it('parses leia with parentheses', function (): void {
    $ast = parseSource('leia(x)');

    expect($ast->statements[0])->toEqual(Nodes::read(['x']));
});

it('parses assignment with arrow operator', function (): void {
    $ast = parseSource('x <- 42');

    expect($ast->statements[0])->toEqual(Nodes::assign('x', Nodes::literal(42)));
});

it('parses assignment with colon-equals operator', function (): void {
    $ast = parseSource('x := 42');

    expect($ast->statements[0])->toEqual(Nodes::assign('x', Nodes::literal(42)));
});

it('parses se/fimse without senao', function (): void {
    $ast = parseSource(<<<'BODY'
        se verdadeiro entao
          escreva "yes"
        fimse
        BODY);

    expect($ast->statements[0])->toEqual(
        Nodes::branch()->true()
            ->then(Nodes::write([Nodes::literal('yes')]))
            ->build(),
    );
});

it('parses se/senao/fimse', function (): void {
    $ast = parseSource(<<<'BODY'
        se verdadeiro entao
          escreva "yes"
        senao
          escreva "no"
        fimse
        BODY);

    expect($ast->statements[0])->toEqual(
        Nodes::branch()->true()
            ->then(Nodes::write([Nodes::literal('yes')]))
            ->otherwise(Nodes::write([Nodes::literal('no')]))
            ->build(),
    );
});

it('parses enquanto/fimenquanto', function (): void {
    $ast = parseSource(<<<'BODY'
        enquanto falso faca
          escreva "loop"
        fimenquanto
        BODY);

    expect($ast->statements[0])->toEqual(
        Nodes::loop()->false()
            ->body(Nodes::write([Nodes::literal('loop')]))
            ->build(),
    );
});

it('parses binary expression', function (): void {
    $ast = parseSource('x <- 1 + 2');

    expect($ast->statements[0])->toEqual(
        Nodes::assign('x', Nodes::binary(Nodes::literal(1), TokenType::PLUS, Nodes::literal(2))),
    );
});

it('parses unary minus', function (): void {
    $ast = parseSource('x <- -5');

    expect($ast->statements[0])->toEqual(
        Nodes::assign('x', Nodes::unary(TokenType::MINUS, Nodes::literal(5))),
    );
});

it('parses variable reference', function (): void {
    $ast = parseSource('x <- y');

    expect($ast->statements[0])->toEqual(Nodes::assign('x', Nodes::variable('y')));
});

it('parses parenthesized expression', function (): void {
    $ast = parseSource('x <- (1 + 2)');

    expect($ast->statements[0])->toEqual(
        Nodes::assign('x', Nodes::binary(Nodes::literal(1), TokenType::PLUS, Nodes::literal(2))),
    );
});

it('parses program with var section', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "teste"
        var
          x: inteiro
        inicio
        escreva "hello"
        fimalgoritmo
        PORTUGOL;

    $ast = new Parser(new Tokenizer($source)->tokenize())->parse();

    expect($ast->statements[0])->toEqual(Nodes::write([Nodes::literal('hello')]));
});
