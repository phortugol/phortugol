<?php

declare(strict_types = 1);

namespace Phortugol\Parser;

use Phortugol\Contracts\Node;
use Phortugol\Enums\TokenType;
use Phortugol\Exceptions\ParseException;
use Phortugol\Lexer\Token;
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

final class Parser
{
    private int $current = 0;

    /**
     * @param list<Token> $tokens
     */
    public function __construct(
        private readonly array $tokens,
    ) {
    }

    public function parse(): ProgramNode
    {
        $this->consume(TokenType::ALGORITMO, 'Expected "algoritmo"');
        $this->consume(TokenType::STRING_LITERAL, 'Expected program name as string');

        if ($this->check(TokenType::VAR)) {
            $this->advance();
            $this->skipVarSection();
        }

        $this->consume(TokenType::INICIO, 'Expected "inicio"');

        $statements = $this->parseBlock([TokenType::FIMALGORITMO]);

        $this->consume(TokenType::FIMALGORITMO, 'Expected "fimalgoritmo"');

        return new ProgramNode($statements);
    }

    /**
     * @param  list<TokenType> $stopAt
     * @return list<Node>
     */
    private function parseBlock(array $stopAt): array
    {
        $statements = [];

        while (! $this->isAtEnd() && ! $this->checkAny($stopAt)) {
            $statements[] = $this->parseStatement();
        }

        return $statements;
    }

    private function parseStatement(): Node
    {
        return match (true) {
            $this->check(TokenType::ESCREVA)  => $this->parseWrite(false),
            $this->check(TokenType::ESCREVAL) => $this->parseWrite(true),
            $this->check(TokenType::LEIA)     => $this->parseRead(),
            $this->check(TokenType::SE)       => $this->parseIf(),
            $this->check(TokenType::ENQUANTO) => $this->parseWhile(),
            default                           => $this->parseAssign(),
        };
    }

    private function parseWrite(bool $newline): WriteNode
    {
        $this->advance();
        $hasParen = $this->match(TokenType::LEFT_PAREN);
        $expressions = [$this->parseExpression()];

        while ($this->match(TokenType::COMMA)) {
            $expressions[] = $this->parseExpression();
        }

        if ($hasParen) {
            $this->consume(TokenType::RIGHT_PAREN, 'Expected ")" after escreva arguments');
        }

        return new WriteNode($expressions, $newline);
    }

    private function parseRead(): ReadNode
    {
        $this->advance();
        $hasParen = $this->match(TokenType::LEFT_PAREN);
        $identifiers = [$this->consume(TokenType::IDENTIFIER, 'Expected variable name')->lexeme];

        while ($this->match(TokenType::COMMA)) {
            $identifiers[] = $this->consume(TokenType::IDENTIFIER, 'Expected variable name')->lexeme;
        }

        if ($hasParen) {
            $this->consume(TokenType::RIGHT_PAREN, 'Expected ")" after leia arguments');
        }

        return new ReadNode($identifiers);
    }

    private function parseAssign(): AssignNode
    {
        $name = $this->consume(TokenType::IDENTIFIER, 'Expected variable name');
        $this->consume(TokenType::ASSIGN, 'Expected "<-" or ":=" after variable name');

        return new AssignNode($name->lexeme, $this->parseExpression());
    }

    private function parseIf(): IfNode
    {
        $this->advance();
        $condition = $this->parseExpression();
        $this->consume(TokenType::ENTAO, 'Expected "entao" after condition');

        $thenBranch = $this->parseBlock([TokenType::SENAO, TokenType::FIMSE]);

        $elseBranch = null;

        if ($this->match(TokenType::SENAO)) {
            $elseBranch = $this->parseBlock([TokenType::FIMSE]);
        }

        $this->consume(TokenType::FIMSE, 'Expected "fimse"');

        return new IfNode($condition, $thenBranch, $elseBranch);
    }

    private function parseWhile(): WhileNode
    {
        $this->advance();
        $condition = $this->parseExpression();
        $this->consume(TokenType::FACA, 'Expected "faca" after condition');

        $body = $this->parseBlock([TokenType::FIMENQUANTO]);

        $this->consume(TokenType::FIMENQUANTO, 'Expected "fimenquanto"');

        return new WhileNode($condition, $body);
    }

    private function parseExpression(): Node
    {
        return $this->parseOr();
    }

    private function parseOr(): Node
    {
        $left = $this->parseAnd();

        while ($this->check(TokenType::OU)) {
            $operator = $this->advance();
            $left = new BinaryNode($left, $operator->type, $this->parseAnd());
        }

        return $left;
    }

    private function parseAnd(): Node
    {
        $left = $this->parseNot();

        while ($this->check(TokenType::E)) {
            $operator = $this->advance();
            $left = new BinaryNode($left, $operator->type, $this->parseNot());
        }

        return $left;
    }

    private function parseNot(): Node
    {
        if ($this->check(TokenType::NAO)) {
            $operator = $this->advance();

            return new UnaryNode($operator->type, $this->parseNot());
        }

        return $this->parseComparison();
    }

    private function parseComparison(): Node
    {
        $left = $this->parseAddition();

        $types = [
            TokenType::EQUAL,
            TokenType::NOT_EQUAL,
            TokenType::LESS,
            TokenType::LESS_EQUAL,
            TokenType::GREATER,
            TokenType::GREATER_EQUAL,
        ];

        while ($this->checkAny($types)) {
            $operator = $this->advance();
            $left = new BinaryNode($left, $operator->type, $this->parseAddition());
        }

        return $left;
    }

    private function parseAddition(): Node
    {
        $left = $this->parseMultiplication();

        while ($this->checkAny([TokenType::PLUS, TokenType::MINUS])) {
            $operator = $this->advance();
            $left = new BinaryNode($left, $operator->type, $this->parseMultiplication());
        }

        return $left;
    }

    private function parseMultiplication(): Node
    {
        $left = $this->parseUnary();

        while ($this->checkAny([TokenType::STAR, TokenType::SLASH, TokenType::DIV, TokenType::MOD])) {
            $operator = $this->advance();
            $left = new BinaryNode($left, $operator->type, $this->parseUnary());
        }

        return $left;
    }

    private function parseUnary(): Node
    {
        if ($this->check(TokenType::MINUS)) {
            $operator = $this->advance();

            return new UnaryNode($operator->type, $this->parseUnary());
        }

        return $this->parsePrimary();
    }

    private function parsePrimary(): Node
    {
        if ($this->check(TokenType::INTEGER_LITERAL)) {
            $token = $this->advance();
            assert(is_int($token->value));

            return new LiteralNode($token->value);
        }

        if ($this->check(TokenType::REAL_LITERAL)) {
            $token = $this->advance();
            assert(is_float($token->value));

            return new LiteralNode($token->value);
        }

        if ($this->check(TokenType::STRING_LITERAL)) {
            $token = $this->advance();
            assert(is_string($token->value));

            return new LiteralNode($token->value);
        }

        if ($this->check(TokenType::VERDADEIRO)) {
            $this->advance();

            return new LiteralNode(true);
        }

        if ($this->check(TokenType::FALSO)) {
            $this->advance();

            return new LiteralNode(false);
        }

        if ($this->check(TokenType::IDENTIFIER)) {
            $token = $this->advance();

            return new VariableNode($token->lexeme);
        }

        if ($this->match(TokenType::LEFT_PAREN)) {
            $expr = $this->parseExpression();
            $this->consume(TokenType::RIGHT_PAREN, 'Expected ")" after expression');

            return $expr;
        }

        $token = $this->peek();

        throw new ParseException("Unexpected token '{$token->lexeme}' at line {$token->line}");
    }

    private function skipVarSection(): void
    {
        while (! $this->isAtEnd() && ! $this->check(TokenType::INICIO)) {
            $this->advance();
        }
    }

    private function consume(TokenType $type, string $message): Token
    {
        if ($this->check($type)) {
            return $this->advance();
        }

        $token = $this->peek();

        throw new ParseException("{$message} at line {$token->line}");
    }

    private function match(TokenType $type): bool
    {
        if (! $this->check($type)) {
            return false;
        }

        $this->advance();

        return true;
    }

    private function check(TokenType $type): bool
    {
        return ! $this->isAtEnd() && $this->peek()->type === $type;
    }

    /**
     * @param list<TokenType> $types
     */
    private function checkAny(array $types): bool
    {
        foreach ($types as $type) {
            if ($this->check($type)) {
                return true;
            }
        }

        return false;
    }

    private function advance(): Token
    {
        if (! $this->isAtEnd()) {
            $this->current++;
        }

        return $this->previous();
    }

    private function peek(): Token
    {
        return $this->tokens[$this->current];
    }

    private function previous(): Token
    {
        return $this->tokens[$this->current - 1];
    }

    private function isAtEnd(): bool
    {
        return $this->peek()->type === TokenType::EOF;
    }
}
