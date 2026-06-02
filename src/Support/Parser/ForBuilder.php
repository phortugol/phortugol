<?php

declare(strict_types = 1);

namespace Phortugol\Support\Parser;

use Phortugol\Concerns\Support\Parser\CanEvaluate;
use Phortugol\Concerns\Support\Parser\HasBody;
use Phortugol\Contracts\Node;
use Phortugol\Exceptions\RuntimeException;
use Phortugol\Parser\Nodes\ForNode;

final class ForBuilder
{
    use CanEvaluate;
    use HasBody;

    private Node | null $from = null;

    private Node | null $to = null;

    private Node | null $step = null;

    /**
     * @var list<Node>
     */
    private array $body = [];

    public function __construct(
        private readonly string $variable,
    ) {
    }

    public function from(Node | int | float $value): static
    {
        $this->from = $this->evaluate($value);

        return $this;
    }

    public function to(Node | int | float $value): static
    {
        $this->to = $this->evaluate($value);

        return $this;
    }

    public function step(Node | int | float $value): static
    {
        $this->step = $this->evaluate($value);

        return $this;
    }

    public function build(): ForNode
    {
        return new ForNode(
            $this->variable,
            $this->from ?? throw new RuntimeException('ForBuilder: call from() before build()'),
            $this->to   ?? throw new RuntimeException('ForBuilder: call to() before build()'),
            $this->step,
            $this->body,
        );
    }
}
