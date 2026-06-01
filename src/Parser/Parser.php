<?php

declare(strict_types = 1);

namespace Phortugol\Parser;

use Phortugol\Contracts\Node;
use Phortugol\Enums\TokenType;
use Phortugol\Lexer\Token;
use Phortugol\Parser\Nodes\ProgramNode;
use Phortugol\Parser\Statements\AssignStatement;
use Phortugol\Parser\Statements\IfStatement;
use Phortugol\Parser\Statements\ReadStatement;
use Phortugol\Parser\Statements\WhileStatement;
use Phortugol\Parser\Statements\WriteStatement;

final readonly class Parser
{
    private TokenStream $stream;

    private Expression $expressions;

    /**
     * @param list<Token> $tokens
     */
    public function __construct(
        array $tokens,
    ) {
        $this->stream = new TokenStream($tokens);
        $this->expressions = new Expression($this->stream);
    }

    public function parse(): ProgramNode
    {
        $this->stream->consume(TokenType::ALGORITMO, 'Expected "algoritmo"');
        $this->stream->consume(TokenType::STRING_LITERAL, 'Expected program name as string');

        if ($this->stream->check(TokenType::VAR)) {
            $this->stream->advance();
            $this->skipVarSection();
        }

        $this->stream->consume(TokenType::INICIO, 'Expected "inicio"');

        $statements = $this->block([TokenType::FIMALGORITMO]);

        $this->stream->consume(TokenType::FIMALGORITMO, 'Expected "fimalgoritmo"');

        return new ProgramNode($statements);
    }

    public function expression(): Node
    {
        return $this->expressions->parse();
    }

    /**
     * @param list<TokenType> $stopAt
     * @return list<Node>
     */
    public function block(array $stopAt): array
    {
        $statements = [];

        while (! $this->stream->isAtEnd && ! $this->stream->checkAny($stopAt)) {
            $statements[] = $this->statement();
        }

        return $statements;
    }

    private function statement(): Node
    {
        return match (true) {
            $this->stream->check(TokenType::ESCREVA),
            $this->stream->check(TokenType::ESCREVAL) => new WriteStatement()->parse($this->stream, $this),
            $this->stream->check(TokenType::LEIA) => new ReadStatement()->parse($this->stream, $this),
            $this->stream->check(TokenType::SE) => new IfStatement()->parse($this->stream, $this),
            $this->stream->check(TokenType::ENQUANTO) => new WhileStatement()->parse($this->stream, $this),
            default => new AssignStatement()->parse($this->stream, $this),
        };
    }

    private function skipVarSection(): void
    {
        while (! $this->stream->isAtEnd && ! $this->stream->check(TokenType::INICIO)) {
            $this->stream->advance();
        }
    }
}
