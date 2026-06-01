<?php

declare(strict_types = 1);

namespace Phortugol\Support\Parser;

use Phortugol\Contracts\Node;
use Phortugol\Enums\TokenType;
use Phortugol\Exceptions\ParseException;
use Phortugol\Lexer\Token;
use Phortugol\Parser\Nodes\AssignNode;
use Phortugol\Parser\Nodes\BinaryNode;
use Phortugol\Parser\Nodes\LiteralNode;
use Phortugol\Parser\Nodes\ProgramNode;
use Phortugol\Parser\Nodes\ReadNode;
use Phortugol\Parser\Nodes\UnaryNode;
use Phortugol\Parser\Nodes\VariableNode;
use Phortugol\Parser\Nodes\WriteNode;

final class Nodes
{
    public static function fromToken(Token $token): Node
    {
        return match ($token->type) {
            TokenType::INTEGER_LITERAL,
            TokenType::REAL_LITERAL,
            TokenType::STRING_LITERAL => new LiteralNode(
                $token->value ?? throw new ParseException("Expected literal value for token '{$token->lexeme}' at line {$token->line}"),
            ),
            TokenType::IDENTIFIER => new VariableNode($token->lexeme),
            default               => throw new ParseException("Cannot build node from token '{$token->lexeme}' at line {$token->line}"),
        };
    }

    public static function literal(int | float | string | bool $value): LiteralNode
    {
        return new LiteralNode($value);
    }

    public static function variable(string $name): VariableNode
    {
        return new VariableNode($name);
    }

    public static function assign(string $name, Node $value): AssignNode
    {
        return new AssignNode($name, $value);
    }

    public static function binary(Node $left, TokenType $operator, Node $right): BinaryNode
    {
        return new BinaryNode($left, $operator, $right);
    }

    public static function unary(TokenType $operator, Node $right): UnaryNode
    {
        return new UnaryNode($operator, $right);
    }

    public static function branch(Node | int | float | string | bool | null $condition = null): BranchBuilder
    {
        return new BranchBuilder($condition);
    }

    public static function loop(Node | int | float | string | bool | null $condition = null): LoopBuilder
    {
        return new LoopBuilder($condition);
    }

    /**
     * @param list<string> $identifiers
     */
    public static function read(array $identifiers): ReadNode
    {
        return new ReadNode($identifiers);
    }

    /**
     * @param list<Node> $expressions
     */
    public static function write(array $expressions): WriteNode
    {
        return new WriteNode($expressions, newline: false);
    }

    /**
     * @param list<Node> $expressions
     */
    public static function writeln(array $expressions): WriteNode
    {
        return new WriteNode($expressions, newline: true);
    }

    /**
     * @param list<Node> $statements
     */
    public static function program(array $statements): ProgramNode
    {
        return new ProgramNode($statements);
    }

    public static function native(): NativeNodes
    {
        return new NativeNodes();
    }
}
