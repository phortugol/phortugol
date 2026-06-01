<?php

declare(strict_types = 1);

use Phortugol\Enums\TokenType;
use Phortugol\Exceptions\LexerException;
use Phortugol\Lexer\Token;
use Phortugol\Lexer\Tokenizer;

// ---------------------------------------------------------------------------
// Keywords
// ---------------------------------------------------------------------------

dataset('keywords', [
    'algoritmo'       => ['algoritmo', TokenType::ALGORITMO],
    'fimalgoritmo'    => ['fimalgoritmo', TokenType::FIMALGORITMO],
    'var'             => ['var', TokenType::VAR],
    'inicio'          => ['inicio', TokenType::INICIO],
    'se'              => ['se', TokenType::SE],
    'entao'           => ['entao', TokenType::ENTAO],
    'senao'           => ['senao', TokenType::SENAO],
    'fimse'           => ['fimse', TokenType::FIMSE],
    'para'            => ['para', TokenType::PARA],
    'de'              => ['de', TokenType::DE],
    'ate'             => ['ate', TokenType::ATE],
    'passo'           => ['passo', TokenType::PASSO],
    'fimpara'         => ['fimpara', TokenType::FIMPARA],
    'enquanto'        => ['enquanto', TokenType::ENQUANTO],
    'faca'            => ['faca', TokenType::FACA],
    'fimenquanto'     => ['fimenquanto', TokenType::FIMENQUANTO],
    'repita'          => ['repita', TokenType::REPITA],
    'escreva'         => ['escreva', TokenType::ESCREVA],
    'escreval'        => ['escreval', TokenType::ESCREVAL],
    'leia'            => ['leia', TokenType::LEIA],
    'e'               => ['e', TokenType::E],
    'ou'              => ['ou', TokenType::OU],
    'nao'             => ['nao', TokenType::NAO],
    'mod'             => ['mod', TokenType::MOD],
    'div'             => ['div', TokenType::DIV],
    'inteiro'         => ['inteiro', TokenType::INTEIRO],
    'real'            => ['real', TokenType::REAL],
    'caractere'       => ['caractere', TokenType::CARACTERE],
    'logico'          => ['logico', TokenType::LOGICO],
    'procedimento'    => ['procedimento', TokenType::PROCEDIMENTO],
    'fimprocedimento' => ['fimprocedimento', TokenType::FIMPROCEDIMENTO],
    'funcao'          => ['funcao', TokenType::FUNCAO],
    'fimfuncao'       => ['fimfuncao', TokenType::FIMFUNCAO],
    'retorne'         => ['retorne', TokenType::RETORNE],
    'interrompa'      => ['interrompa', TokenType::INTERROMPA],
    'caso'            => ['caso', TokenType::CASO],
    'fimcaso'         => ['fimcaso', TokenType::FIMCASO],
    'seja'            => ['seja', TokenType::SEJA],
    'outrocaso'       => ['outrocaso', TokenType::OUTROCASO],
    'vetor'           => ['vetor', TokenType::VETOR],
]);

it('tokenizes %s as a keyword', function (string $source, TokenType $expected): void {
    [$token] = new Tokenizer($source)->tokenize();

    expect($token->type)->toBe($expected);
})->with('keywords');

it('tokenizes keywords case-insensitively', function (): void {
    [$upper] = new Tokenizer('ALGORITMO')->tokenize();
    [$mixed] = new Tokenizer('Algoritmo')->tokenize();

    expect($upper->type)->toBe(TokenType::ALGORITMO)
        ->and($mixed->type)->toBe(TokenType::ALGORITMO);
});

// ---------------------------------------------------------------------------
// Boolean literals
// ---------------------------------------------------------------------------

it('tokenizes verdadeiro with value true', function (): void {
    [$token] = new Tokenizer('verdadeiro')->tokenize();

    expect($token->type)->toBe(TokenType::VERDADEIRO)
        ->and($token->value)->toBeTrue();
});

it('tokenizes falso with value false', function (): void {
    [$token] = new Tokenizer('falso')->tokenize();

    expect($token->type)->toBe(TokenType::FALSO)
        ->and($token->value)->toBeFalse();
});

// ---------------------------------------------------------------------------
// Operators
// ---------------------------------------------------------------------------

dataset('operators', [
    'assign arrow'    => ['<-', TokenType::ASSIGN],
    'assign colon-eq' => [':=', TokenType::ASSIGN],
    'plus'            => ['+', TokenType::PLUS],
    'minus'           => ['-', TokenType::MINUS],
    'star'            => ['*', TokenType::STAR],
    'slash'           => ['/', TokenType::SLASH],
    'equal'           => ['=', TokenType::EQUAL],
    'not equal'       => ['<>', TokenType::NOT_EQUAL],
    'less'            => ['<', TokenType::LESS],
    'greater'         => ['>', TokenType::GREATER],
    'less equal'      => ['<=', TokenType::LESS_EQUAL],
    'greater equal'   => ['>=', TokenType::GREATER_EQUAL],
]);

it('tokenizes operator %s', function (string $source, TokenType $expected): void {
    [$token] = new Tokenizer($source)->tokenize();

    expect($token->type)->toBe($expected);
})->with('operators');

it('stores the operator lexeme correctly', function (): void {
    [$token] = new Tokenizer('<-')->tokenize();

    expect($token->lexeme)->toBe('<-');
});

// ---------------------------------------------------------------------------
// Symbols
// ---------------------------------------------------------------------------

dataset('symbols', [
    'left paren'    => ['(', TokenType::LEFT_PAREN],
    'right paren'   => [')', TokenType::RIGHT_PAREN],
    'left bracket'  => ['[', TokenType::LEFT_BRACKET],
    'right bracket' => [']', TokenType::RIGHT_BRACKET],
    'comma'         => [',', TokenType::COMMA],
    'colon'         => [':', TokenType::COLON],
    'dot'           => ['.', TokenType::DOT],
    'dotdot'        => ['..', TokenType::DOTDOT],
]);

it('tokenizes symbol %s', function (string $source, TokenType $expected): void {
    [$token] = new Tokenizer($source)->tokenize();

    expect($token->type)->toBe($expected);
})->with('symbols');

it('disambiguates dot from dotdot', function (): void {
    $tokens = new Tokenizer('1..5')->tokenize();

    expect($tokens[0]->type)->toBe(TokenType::INTEGER_LITERAL)
        ->and($tokens[1]->type)->toBe(TokenType::DOTDOT)
        ->and($tokens[2]->type)->toBe(TokenType::INTEGER_LITERAL);
});

// ---------------------------------------------------------------------------
// Numeric literals
// ---------------------------------------------------------------------------

it('tokenizes integer literal', function (): void {
    [$token] = new Tokenizer('42')->tokenize();

    expect($token->type)->toBe(TokenType::INTEGER_LITERAL)
        ->and($token->value)->toBe(42);
});

it('tokenizes real literal', function (): void {
    [$token] = new Tokenizer('3.14')->tokenize();

    expect($token->type)->toBe(TokenType::REAL_LITERAL)
        ->and($token->value)->toBe(3.14);
});

it('does not treat trailing dot as real', function (): void {
    $tokens = new Tokenizer('5.')->tokenize();

    expect($tokens[0]->type)->toBe(TokenType::INTEGER_LITERAL)
        ->and($tokens[1]->type)->toBe(TokenType::DOT);
});

// ---------------------------------------------------------------------------
// String literals
// ---------------------------------------------------------------------------

it('tokenizes string literal', function (): void {
    [$token] = new Tokenizer('"hello world"')->tokenize();

    expect($token->type)->toBe(TokenType::STRING_LITERAL)
        ->and($token->value)->toBe('hello world');
});

it('tokenizes empty string', function (): void {
    [$token] = new Tokenizer('""')->tokenize();

    expect($token->type)->toBe(TokenType::STRING_LITERAL)
        ->and($token->value)->toBe('');
});

it('tokenizes multiline string and tracks line', function (): void {
    // "line1\nline2" spans lines 1-2; the \n after the closing " moves to 3
    $tokens = new Tokenizer("\"line1\nline2\"\nfim")->tokenize();

    expect($tokens[0]->type)->toBe(TokenType::STRING_LITERAL)
        ->and($tokens[1]->line)->toBe(3);
});

// ---------------------------------------------------------------------------
// Identifiers
// ---------------------------------------------------------------------------

it('tokenizes identifier', function (): void {
    [$token] = new Tokenizer('minhaVariavel')->tokenize();

    expect($token->type)->toBe(TokenType::IDENTIFIER)
        ->and($token->value)->toBe('minhaVariavel');
});

it('tokenizes identifier starting with underscore', function (): void {
    [$token] = new Tokenizer('_contador')->tokenize();

    expect($token->type)->toBe(TokenType::IDENTIFIER);
});

it('preserves identifier case', function (): void {
    [$token] = new Tokenizer('MinhaVar')->tokenize();

    expect($token->value)->toBe('MinhaVar');
});

it('does not confuse identifiers that start with a keyword prefix', function (): void {
    // 'semente' starts with 'se' but is not a keyword
    [$token] = new Tokenizer('semente')->tokenize();

    expect($token->type)->toBe(TokenType::IDENTIFIER);
});

// ---------------------------------------------------------------------------
// Comments
// ---------------------------------------------------------------------------

it('skips line comments', function (): void {
    $tokens = new Tokenizer("// comentario\nalgo")->tokenize();

    expect($tokens[0]->type)->toBe(TokenType::IDENTIFIER)
        ->and($tokens[0]->lexeme)->toBe('algo');
});

it('skips block comments', function (): void {
    $tokens = new Tokenizer('{ comentario } algo')->tokenize();

    expect($tokens[0]->type)->toBe(TokenType::IDENTIFIER)
        ->and($tokens[0]->lexeme)->toBe('algo');
});

it('tracks lines inside block comments', function (): void {
    $tokens = new Tokenizer("{\nlinha2\n} fim")->tokenize();

    expect($tokens[0]->line)->toBe(3);
});

// ---------------------------------------------------------------------------
// Line tracking
// ---------------------------------------------------------------------------

it('tracks line numbers', function (): void {
    $tokens = new Tokenizer("a\nb\nc")->tokenize();

    expect($tokens[0]->line)->toBe(1)
        ->and($tokens[1]->line)->toBe(2)
        ->and($tokens[2]->line)->toBe(3);
});

// ---------------------------------------------------------------------------
// EOF
// ---------------------------------------------------------------------------

it('always appends an EOF token', function (): void {
    $tokens = new Tokenizer('')->tokenize();

    expect($tokens)->toHaveCount(1)
        ->and($tokens[0]->type)->toBe(TokenType::EOF);
});

it('EOF token has empty lexeme', function (): void {
    $tokens = new Tokenizer('a')->tokenize();
    $eof = end($tokens);

    expect($eof)->toBeInstanceOf(Token::class)
        ->and($eof->type)->toBe(TokenType::EOF)
        ->and($eof->lexeme)->toBe('');
});

// ---------------------------------------------------------------------------
// Full program
// ---------------------------------------------------------------------------

it('tokenizes a minimal algoritmo block', function (): void {
    $source = <<<'PORTUGOL'
        algoritmo "ola"
        var x: inteiro
        inicio
            x <- 1
            escreva(x)
        fimalgoritmo
        PORTUGOL;

    $types = array_map(
        fn (Token $t): TokenType => $t->type,
        new Tokenizer($source)->tokenize(),
    );

    expect($types)->toContain(TokenType::ALGORITMO)
        ->toContain(TokenType::VAR)
        ->toContain(TokenType::INICIO)
        ->toContain(TokenType::ASSIGN)
        ->toContain(TokenType::ESCREVA)
        ->toContain(TokenType::FIMALGORITMO)
        ->toContain(TokenType::EOF);
});

// ---------------------------------------------------------------------------
// Error cases
// ---------------------------------------------------------------------------

it('throws LexerException on unterminated string', function (): void {
    new Tokenizer('"sem fechar')->tokenize();
})->throws(LexerException::class, 'Unterminated string');

it('throws LexerException on unterminated block comment', function (): void {
    new Tokenizer('{ sem fechar')->tokenize();
})->throws(LexerException::class, 'Unterminated block comment');

it('throws LexerException on unexpected character', function (): void {
    new Tokenizer('@')->tokenize();
})->throws(LexerException::class, "Unexpected character '@'");
