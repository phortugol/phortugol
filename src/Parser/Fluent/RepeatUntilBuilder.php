<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Fluent;

use Phortugol\Concerns\Parser\Fluent\HasCondition;
use Phortugol\Concerns\Parser\Fluent\HasLoopBody;
use Phortugol\Concerns\Parser\Fluent\HasPortugueseCondition;
use Phortugol\Concerns\Parser\Fluent\HasPortugueseLoopBody;
use Phortugol\Contracts\Node;
use Phortugol\Exceptions\RuntimeException;
use Phortugol\Parser\Nodes\RepeatUntilNode;

final class RepeatUntilBuilder
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

    public static function make(): RepeatUntilBuilder
    {
        return new RepeatUntilBuilder();
    }

    public function create(): RepeatUntilNode
    {
        return new RepeatUntilNode(
            $this->body,
            $this->condition ?? throw new RuntimeException('RepeatUntilBuilder: call when(), true(), false(), literal(), or variable() before build()'),
        );
    }
}
