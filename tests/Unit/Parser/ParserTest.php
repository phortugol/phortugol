<?php

declare(strict_types = 1);

use Phortugol\Lexer\Tokenizer;
use Phortugol\Parser\Nodes\AssignNode;
use Phortugol\Parser\Nodes\BinaryNode;
use Phortugol\Parser\Nodes\IfNode;
use Phortugol\Parser\Nodes\LiteralNode;
use Phortugol\Parser\Nodes\ProgramNode;
use Phortugol\Parser\Nodes\ReadNode;
use Phortugol\Parser\Nodes\UnaryNode;
use Phortugol\Parser\Nodes\VariableNode;
use Phortugol\Parser\Nodes\WhileNode;
use Phortugol\Parser\Nodes\WriteNode;
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

    expect($ast->statements[0])->toBeInstanceOf(WriteNode::class)
        ->and($ast->statements[0]->newline)->toBeFalse()
        ->and($ast->statements[0]->expressions[0])->toBeInstanceOf(LiteralNode::class)
        ->and($ast->statements[0]->expressions[0]->value)->toBe('hello');
});

it('parses escreval as WriteNode with newline', function (): void {
    $ast = parseSource('escreval "hello"');

    expect($ast->statements[0])->toBeInstanceOf(WriteNode::class)
        ->and($ast->statements[0]->newline)->toBeTrue();
});

it('parses escreva with parentheses', function (): void {
    $ast = parseSource('escreva("hello")');

    expect($ast->statements[0])->toBeInstanceOf(WriteNode::class)
        ->and($ast->statements[0]->expressions[0])->toBeInstanceOf(LiteralNode::class);
});

it('parses escreva with multiple expressions', function (): void {
    $ast = parseSource('escreva "a", "b", "c"');

    expect($ast->statements[0])->toBeInstanceOf(WriteNode::class)
        ->and($ast->statements[0]->expressions)->toHaveCount(3);
});

it('parses leia as ReadNode', function (): void {
    $ast = parseSource('leia x');

    expect($ast->statements[0])->toBeInstanceOf(ReadNode::class)
        ->and($ast->statements[0]->identifiers)->toBe(['x']);
});

it('parses leia with multiple variables', function (): void {
    $ast = parseSource('leia x, y, z');

    expect($ast->statements[0])->toBeInstanceOf(ReadNode::class)
        ->and($ast->statements[0]->identifiers)->toBe(['x', 'y', 'z']);
});

it('parses leia with parentheses', function (): void {
    $ast = parseSource('leia(x)');

    expect($ast->statements[0])->toBeInstanceOf(ReadNode::class)
        ->and($ast->statements[0]->identifiers)->toBe(['x']);
});

it('parses assignment with arrow operator', function (): void {
    $ast = parseSource('x <- 42');

    expect($ast->statements[0])->toBeInstanceOf(AssignNode::class)
        ->and($ast->statements[0]->name)->toBe('x')
        ->and($ast->statements[0]->value)->toBeInstanceOf(LiteralNode::class)
        ->and($ast->statements[0]->value->value)->toBe(42);
});

it('parses assignment with colon-equals operator', function (): void {
    $ast = parseSource('x := 42');

    expect($ast->statements[0])->toBeInstanceOf(AssignNode::class);
});

it('parses se/fimse without senao', function (): void {
    $ast = parseSource(<<<'BODY'
        se verdadeiro entao
          escreva "yes"
        fimse
        BODY);

    expect($ast->statements[0])->toBeInstanceOf(IfNode::class)
        ->and($ast->statements[0]->thenBranch)->toHaveCount(1)
        ->and($ast->statements[0]->elseBranch)->toBeNull();
});

it('parses se/senao/fimse', function (): void {
    $ast = parseSource(<<<'BODY'
        se verdadeiro entao
          escreva "yes"
        senao
          escreva "no"
        fimse
        BODY);

    expect($ast->statements[0])->toBeInstanceOf(IfNode::class)
        ->and($ast->statements[0]->thenBranch)->toHaveCount(1)
        ->and($ast->statements[0]->elseBranch)->toHaveCount(1);
});

it('parses enquanto/fimenquanto', function (): void {
    $ast = parseSource(<<<'BODY'
        enquanto falso faca
          escreva "loop"
        fimenquanto
        BODY);

    expect($ast->statements[0])->toBeInstanceOf(WhileNode::class)
        ->and($ast->statements[0]->body)->toHaveCount(1);
});

it('parses binary expression', function (): void {
    $ast = parseSource('x <- 1 + 2');

    $assign = $ast->statements[0];
    expect($assign)->toBeInstanceOf(AssignNode::class)
        ->and($assign->value)->toBeInstanceOf(BinaryNode::class);
});

it('parses unary minus', function (): void {
    $ast = parseSource('x <- -5');

    $assign = $ast->statements[0];
    expect($assign)->toBeInstanceOf(AssignNode::class)
        ->and($assign->value)->toBeInstanceOf(UnaryNode::class)
        ->and($assign->value->right)->toBeInstanceOf(LiteralNode::class)
        ->and($assign->value->right->value)->toBe(5);
});

it('parses variable reference', function (): void {
    $ast = parseSource('x <- y');

    $assign = $ast->statements[0];
    expect($assign)->toBeInstanceOf(AssignNode::class)
        ->and($assign->value)->toBeInstanceOf(VariableNode::class)
        ->and($assign->value->name)->toBe('y');
});

it('parses parenthesized expression', function (): void {
    $ast = parseSource('x <- (1 + 2)');

    $assign = $ast->statements[0];
    expect($assign)->toBeInstanceOf(AssignNode::class)
        ->and($assign->value)->toBeInstanceOf(BinaryNode::class);
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

    expect($ast->statements[0])->toBeInstanceOf(WriteNode::class);
});
