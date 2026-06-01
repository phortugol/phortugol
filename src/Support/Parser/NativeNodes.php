<?php

declare(strict_types = 1);

namespace Phortugol\Support\Parser;

use Phortugol\Contracts\Node;
use Phortugol\Enums\TokenType;
use Phortugol\Lexer\Token;
use Phortugol\Parser\Nodes\AssignNode;
use Phortugol\Parser\Nodes\BinaryNode;
use Phortugol\Parser\Nodes\LiteralNode;
use Phortugol\Parser\Nodes\ProgramNode;
use Phortugol\Parser\Nodes\ReadNode;
use Phortugol\Parser\Nodes\UnaryNode;
use Phortugol\Parser\Nodes\VariableNode;
use Phortugol\Parser\Nodes\WriteNode;

final readonly class NativeNodes
{
    public function fromToken(Token $token): Node
    {
        return Nodes::fromToken($token);
    }

    public function verdadeiro(): LiteralNode
    {
        return Nodes::literal(true);
    }

    public function falso(): LiteralNode
    {
        return Nodes::literal(false);
    }

    public function variavel(string $nome): VariableNode
    {
        return Nodes::variable($nome);
    }

    public function atribuir(string $nome, Node $valor): AssignNode
    {
        return Nodes::assign($nome, $valor);
    }

    public function binario(Node $esquerda, TokenType $operador, Node $direita): BinaryNode
    {
        return Nodes::binary($esquerda, $operador, $direita);
    }

    public function unario(TokenType $operador, Node $direita): UnaryNode
    {
        return Nodes::unary($operador, $direita);
    }

    public function se(Node | int | float | string | bool | null $condicao = null): NativeBranchBuilder
    {
        return new NativeBranchBuilder($condicao);
    }

    public function enquanto(Node | int | float | string | bool | null $condicao = null): NativeLoopBuilder
    {
        return new NativeLoopBuilder($condicao);
    }

    /**
     * @param list<string> $identificadores
     */
    public function leia(array $identificadores): ReadNode
    {
        return Nodes::read($identificadores);
    }

    /**
     * @param list<Node> $expressoes
     */
    public function escreva(array $expressoes): WriteNode
    {
        return Nodes::write($expressoes);
    }

    /**
     * @param list<Node> $expressoes
     */
    public function escreval(array $expressoes): WriteNode
    {
        return Nodes::writeln($expressoes);
    }

    /**
     * @param list<Node> $declaracoes
     */
    public function algoritmo(array $declaracoes): ProgramNode
    {
        return Nodes::program($declaracoes);
    }
}
