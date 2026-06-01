<?php

declare(strict_types = 1);

namespace Phortugol\Support\Parser;

use Phortugol\Contracts\Node;
use Phortugol\Parser\Nodes\IfNode;

final class NativeBranchBuilder
{
    private readonly BranchBuilder $builder;

    public function __construct(Node | int | float | string | bool | null $condicao = null)
    {
        $this->builder = new BranchBuilder($condicao);
    }

    public function verdadeiro(): NativeBranchBuilder
    {
        $this->builder->true();

        return $this;
    }

    public function falso(): NativeBranchBuilder
    {
        $this->builder->false();

        return $this;
    }

    public function literal(int | float | string | bool $valor): NativeBranchBuilder
    {
        $this->builder->literal($valor);

        return $this;
    }

    public function variavel(string $nome): NativeBranchBuilder
    {
        $this->builder->variable($nome);

        return $this;
    }

    public function quando(Node | int | float | string | bool $condicao): NativeBranchBuilder
    {
        $this->builder->when($condicao);

        return $this;
    }

    public function entao(Node ...$declaracoes): NativeBranchBuilder
    {
        $this->builder->then(...$declaracoes);

        return $this;
    }

    public function senao(Node ...$declaracoes): NativeBranchBuilder
    {
        $this->builder->otherwise(...$declaracoes);

        return $this;
    }

    public function build(): IfNode
    {
        return $this->builder->build();
    }
}
