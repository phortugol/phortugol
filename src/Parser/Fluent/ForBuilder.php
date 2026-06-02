<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Fluent;

use Phortugol\Concerns\Parser\Fluent\CanEvaluate;
use Phortugol\Concerns\Parser\Fluent\HasLoopBody;
use Phortugol\Concerns\Parser\Fluent\HasPortugueseForRange;
use Phortugol\Concerns\Parser\Fluent\HasPortugueseLoopBody;
use Phortugol\Contracts\Node;
use Phortugol\Exceptions\RuntimeException;
use Phortugol\Parser\Nodes\ForNode;

final class ForBuilder
{
    use CanEvaluate;
    use HasLoopBody;
    use HasPortugueseLoopBody;
    use HasPortugueseForRange;

    private Node | null $from = null;

    private Node | null $to = null;

    private Node | null $step = null;

    /**
     * @var list<Node>
     */
    private array $body = [];

    public static function make(string $variable): ForBuilder
    {
        return new ForBuilder($variable);
    }

    public function __construct(
        private readonly string $variable,
    ) {
    }

    public function from(Node | int | float $value): ForBuilder
    {
        $this->from = $this->evaluate($value);

        return $this;
    }

    public function to(Node | int | float $value): ForBuilder
    {
        $this->to = $this->evaluate($value);

        return $this;
    }

    public function step(Node | int | float $value): ForBuilder
    {
        $this->step = $this->evaluate($value);

        return $this;
    }

    public function create(): ForNode
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
