<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Fluent;

use Phortugol\Concerns\Parser\Fluent\HasForRange;
use Phortugol\Concerns\Parser\Fluent\HasLoopBody;
use Phortugol\Contracts\Node;
use Phortugol\Exceptions\RuntimeException;
use Phortugol\Parser\Nodes\ForNode;

/**
 * @internal Use Syntax::forLoop() or Syntax::para() instead.
 */
final class ForBuilder
{
    use HasForRange;
    use HasLoopBody;

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

    public function create(): ForNode
    {
        return new ForNode(
            $this->variable,
            $this->from ?? throw new RuntimeException('ForBuilder: call from() before create()'),
            $this->to   ?? throw new RuntimeException('ForBuilder: call to() before create()'),
            $this->step,
            $this->body,
        );
    }
}
