<?php

declare(strict_types = 1);

namespace Phortugol\Support\Parser;

use Phortugol\Contracts\Node;
use Phortugol\Parser\Nodes\WhileNode;

final class NativeLoopBuilder
{
    private readonly LoopBuilder $builder;

    public function __construct(Node | int | float | string | bool | null $condicao = null)
    {
        $this->builder = new LoopBuilder($condicao);
    }

    public function verdadeiro(): NativeLoopBuilder
    {
        $this->builder->true();

        return $this;
    }

    public function falso(): NativeLoopBuilder
    {
        $this->builder->false();

        return $this;
    }

    public function literal(int | float | string | bool $valor): NativeLoopBuilder
    {
        $this->builder->literal($valor);

        return $this;
    }

    public function variavel(string $nome): NativeLoopBuilder
    {
        $this->builder->variable($nome);

        return $this;
    }

    public function quando(Node | int | float | string | bool $condicao): NativeLoopBuilder
    {
        $this->builder->when($condicao);

        return $this;
    }

    public function faca(Node ...$declaracoes): NativeLoopBuilder
    {
        $this->builder->body(...$declaracoes);

        return $this;
    }

    public function build(): WhileNode
    {
        return $this->builder->build();
    }
}
