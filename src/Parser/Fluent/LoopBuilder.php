<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Fluent;

use Phortugol\Concerns\Parser\Fluent\HasCondition;
use Phortugol\Concerns\Parser\Fluent\HasLoopBody;
use Phortugol\Concerns\Parser\Fluent\HasPortugueseCondition;
use Phortugol\Concerns\Parser\Fluent\HasPortugueseLoopBody;
use Phortugol\Contracts\Node;
use Phortugol\Exceptions\RuntimeException;
use Phortugol\Parser\Nodes\WhileNode;

final class LoopBuilder
{
    use HasCondition;
    use HasLoopBody;
    use HasPortugueseCondition;
    use HasPortugueseLoopBody;

    private Node | null $condition = null;

    /**
     * @var list<Node>
     */
    private array $body = [];

    public static function make(
        Node | int | float | string | bool | null $condition = null,
    ): LoopBuilder {
        return new LoopBuilder($condition);
    }

    public function __construct(
        Node | int | float | string | bool | null $condition = null,
    ) {
        if ($condition !== null) {
            $this->condition = $this->evaluate($condition);
        }
    }

    public function create(): WhileNode
    {
        return new WhileNode(
            $this->condition ?? throw new RuntimeException('Loop condition not set — call when(), true(), false(), literal(), or variable() before build()'),
            $this->body,
        );
    }
}
