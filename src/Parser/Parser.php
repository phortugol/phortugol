<?php

declare(strict_types = 1);

namespace Phortugol\Parser;

use Phortugol\Contracts\Node;
use Phortugol\Enums\TokenType;
use Phortugol\Lexer\Token;
use Phortugol\Parser\Nodes\ArrayDeclNode;
use Phortugol\Parser\Nodes\ProgramNode;
use Phortugol\Parser\Statements\AssignStatement;
use Phortugol\Parser\Statements\BreakStatement;
use Phortugol\Parser\Statements\ForStatement;
use Phortugol\Parser\Statements\IfStatement;
use Phortugol\Parser\Statements\ReadStatement;
use Phortugol\Parser\Statements\RepeatUntilStatement;
use Phortugol\Parser\Statements\SwitchStatement;
use Phortugol\Parser\Statements\WhileStatement;
use Phortugol\Parser\Statements\WriteStatement;

final readonly class Parser
{
    public TokenStream $stream;

    private Expression $expression;

    /**
     * @param list<Token> $tokens
     */
    public function __construct(
        array $tokens,
    ) {
        $this->stream = new TokenStream($tokens);
        $this->expression = new Expression($this->stream);
    }

    public function parse(): ProgramNode
    {
        $this->stream->consume(type: TokenType::ALGORITMO, message: 'Expected "algoritmo"');
        $this->stream->consume(type: TokenType::STRING_LITERAL, message: 'Expected program name as string');

        $declarations = [];

        if ($this->stream->check(type: TokenType::VAR)) {
            $this->stream->advance();
            $declarations = $this->parseVarSection();
        }

        $this->stream->consume(type: TokenType::INICIO, message: 'Expected "inicio"');

        $statements = $this->block(stopAt: [TokenType::FIMALGORITMO]);

        $this->stream->consume(type: TokenType::FIMALGORITMO, message: 'Expected "fimalgoritmo"');

        return new ProgramNode(array_merge($declarations, $statements));
    }

    public function expression(): Node
    {
        return $this->expression->parse();
    }

    /**
     * @param  list<TokenType> $stopAt
     * @return list<Node>
     */
    public function block(array $stopAt): array
    {
        $statements = [];

        while (! $this->stream->isAtEnd && ! $this->stream->checkAny(types: $stopAt)) {
            $statements[] = $this->statement();
        }

        return $statements;
    }

    private function statement(): Node
    {
        return (match (true) {
            $this->stream->check(TokenType::ESCREVA),
            $this->stream->check(TokenType::ESCREVAL)   => new WriteStatement(),
            $this->stream->check(TokenType::LEIA)       => new ReadStatement(),
            $this->stream->check(TokenType::SE)         => new IfStatement(),
            $this->stream->check(TokenType::ENQUANTO)   => new WhileStatement(),
            $this->stream->check(TokenType::PARA)       => new ForStatement(),
            $this->stream->check(TokenType::REPITA)     => new RepeatUntilStatement(),
            $this->stream->check(TokenType::SEJA)       => new SwitchStatement(),
            $this->stream->check(TokenType::INTERROMPA) => new BreakStatement(),
            default                                     => new AssignStatement(),
        })($this);
    }

    /**
     * @return list<ArrayDeclNode>
     */
    private function parseVarSection(): array
    {
        $declarations = [];

        while (! $this->stream->isAtEnd && ! $this->stream->check(TokenType::INICIO)) {
            if (! $this->stream->check(TokenType::IDENTIFIER)) {
                $this->stream->advance();

                continue;
            }

            $name = $this->stream->advance();
            $this->stream->consume(type: TokenType::COLON, message: 'Expected ":" after variable name');

            if ($this->stream->match(TokenType::VETOR)) {
                $this->stream->consume(type: TokenType::LEFT_BRACKET, message: 'Expected "[" after "vetor"');
                $start = $this->expression->parse();
                $this->stream->consume(type: TokenType::DOTDOT, message: 'Expected ".." in vector range');
                $end = $this->expression->parse();
                $this->stream->consume(type: TokenType::RIGHT_BRACKET, message: 'Expected "]" after vector range');
                $this->stream->consume(type: TokenType::DE, message: 'Expected "de" after vector range');
                $this->stream->advance();
                $declarations[] = new ArrayDeclNode($name->lexeme, $start, $end);
            } else {
                $this->stream->advance();
            }
        }

        return $declarations;
    }
}
