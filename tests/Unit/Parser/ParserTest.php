<?php

declare(strict_types = 1);

use Phortugol\Enums\TokenType;
use Phortugol\Lexer\Tokenizer;
use Phortugol\Parser\Fluent\Syntax;
use Phortugol\Parser\Nodes\ProgramNode;
use Phortugol\Parser\Parser;

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

    expect($ast->statements[0])->toEqual(Syntax::write('hello'));
});

it('parses escreval as WriteNode with newline', function (): void {
    $ast = parseSource('escreval "hello"');

    expect($ast->statements[0])->toEqual(Syntax::writeln('hello'));
});

it('parses escreva with parentheses', function (): void {
    $ast = parseSource('escreva("hello")');

    expect($ast->statements[0])->toEqual(Syntax::write('hello'));
});

it('parses escreva with multiple expressions', function (): void {
    $ast = parseSource('escreva "a", "b", "c"');

    expect($ast->statements[0])->toEqual(Syntax::write('a', 'b', 'c'));
});

it('parses leia as ReadNode', function (): void {
    $ast = parseSource('leia x');

    expect($ast->statements[0])->toEqual(Syntax::read('x'));
});

it('parses leia with multiple variables', function (): void {
    $ast = parseSource('leia x, y, z');

    expect($ast->statements[0])->toEqual(Syntax::read('x', 'y', 'z'));
});

it('parses leia with parentheses', function (): void {
    $ast = parseSource('leia(x)');

    expect($ast->statements[0])->toEqual(Syntax::read('x'));
});

it('parses assignment with arrow operator', function (): void {
    $ast = parseSource('x <- 42');

    expect($ast->statements[0])->toEqual(Syntax::assign('x', Syntax::literal(42)));
});

it('parses assignment with colon-equals operator', function (): void {
    $ast = parseSource('x := 42');

    expect($ast->statements[0])->toEqual(Syntax::assign('x', Syntax::literal(42)));
});

it('parses se/fimse without senao', function (): void {
    $ast = parseSource(<<<'BODY'
        se verdadeiro entao
          escreva "yes"
        fimse
        BODY);

    expect($ast->statements[0])->toEqual(
        Syntax::branch()->true()
            ->then(Syntax::write('yes'))
            ->create(),
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
        Syntax::branch()->true()
            ->then(Syntax::write('yes'))
            ->otherwise(Syntax::write('no'))
            ->create(),
    );
});

it('parses enquanto/fimenquanto', function (): void {
    $ast = parseSource(<<<'BODY'
        enquanto falso faca
          escreva "loop"
        fimenquanto
        BODY);

    expect($ast->statements[0])->toEqual(
        Syntax::loop()->false()
            ->body(Syntax::write('loop'))
            ->create(),
    );
});

it('parses binary expression', function (): void {
    $ast = parseSource('x <- 1 + 2');

    expect($ast->statements[0])->toEqual(
        Syntax::assign('x', Syntax::binary(Syntax::literal(1), TokenType::PLUS, Syntax::literal(2))),
    );
});

it('parses unary minus', function (): void {
    $ast = parseSource('x <- -5');

    expect($ast->statements[0])->toEqual(
        Syntax::assign('x', Syntax::unary(TokenType::MINUS, Syntax::literal(5))),
    );
});

it('parses variable reference', function (): void {
    $ast = parseSource('x <- y');

    expect($ast->statements[0])->toEqual(Syntax::assign('x', Syntax::variable('y')));
});

it('parses parenthesized expression', function (): void {
    $ast = parseSource('x <- (1 + 2)');

    expect($ast->statements[0])->toEqual(
        Syntax::assign('x', Syntax::binary(Syntax::literal(1), TokenType::PLUS, Syntax::literal(2))),
    );
});

it('parses para/fimpara without passo', function (): void {
    $ast = parseSource(<<<'BODY'
        para i de 1 ate 5 faca
          escreva i
        fimpara
        BODY);

    expect($ast->statements[0])->toEqual(
        Syntax::forLoop('i')->from(1)->to(5)
            ->body(Syntax::write(Syntax::variable('i')))
            ->create(),
    );
});

it('parses para/fimpara with passo', function (): void {
    $ast = parseSource(<<<'BODY'
        para i de 0 ate 10 passo 2 faca
          escreva i
        fimpara
        BODY);

    expect($ast->statements[0])->toEqual(
        Syntax::forLoop('i')->from(0)->to(10)->step(2)
            ->body(Syntax::write(Syntax::variable('i')))
            ->create(),
    );
});

it('parses repita/ate', function (): void {
    $ast = parseSource(<<<'BODY'
        repita
          escreva "loop"
        ate verdadeiro
        BODY);

    expect($ast->statements[0])->toEqual(
        Syntax::repeatUntil()->true()
            ->body(Syntax::write('loop'))
            ->create(),
    );
});

it('parses seja/caso/fimcaso without outrocaso', function (): void {
    $ast = parseSource(<<<'BODY'
        seja x
          caso 1
            escreva "um"
          caso 2
            escreva "dois"
        fimcaso
        BODY);

    $node = $ast->statements[0];
    expect($node)->toBeInstanceOf(\Phortugol\Parser\Nodes\SwitchNode::class)
        ->and($node->cases)->toHaveCount(2)
        ->and($node->otherwise)->toBeNull();
});

it('parses seja/caso/outrocaso/fimcaso', function (): void {
    $ast = parseSource(<<<'BODY'
        seja x
          caso 1
            escreva "um"
          outrocaso
            escreva "outro"
        fimcaso
        BODY);

    $node = $ast->statements[0];
    expect($node)->toBeInstanceOf(\Phortugol\Parser\Nodes\SwitchNode::class)
        ->and($node->cases)->toHaveCount(1)
        ->and($node->otherwise)->not->toBeNull();
});

it('parses interrompa as BreakNode', function (): void {
    $ast = parseSource(<<<'BODY'
        enquanto verdadeiro faca
          interrompa
        fimenquanto
        BODY);

    $while = $ast->statements[0];
    expect($while->body[0])->toBeInstanceOf(\Phortugol\Parser\Nodes\BreakNode::class);
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

    expect($ast->statements[0])->toEqual(Syntax::write('hello'));
});
