<?php

declare(strict_types = 1);

namespace Phortugol\Support\Parser;

use Phortugol\Concerns\Support\Parser\CanBeFalse;
use Phortugol\Concerns\Support\Parser\CanBeLiteral;
use Phortugol\Concerns\Support\Parser\CanBeTrue;
use Phortugol\Concerns\Support\Parser\CanBeVariable;
use Phortugol\Concerns\Support\Parser\CanWhen;
use Phortugol\Concerns\Support\Parser\HasBody;
use Phortugol\Contracts\Node;
use Phortugol\Parser\Nodes\WhileNode;

final class LoopBuilder
{
    use CanBeTrue;
    use CanBeFalse;
    use CanBeLiteral;
    use CanBeVariable;
    use CanWhen;
    use HasBody;

    private Node | null $condition = null;

    /**
     * @var list<Node>
     */
    private array $body = [];

    public function __construct(
        Node | int | float | string | bool | null $condition = null,
    ) {
        if ($condition !== null) {
            $this->condition = $this->evaluate($condition);
        }
    }

    public function build(): WhileNode
    {
        return new WhileNode(
            $this->condition ?? throw new \LogicException('Loop condition not set — call when(), true(), false(), literal(), or variable() before build()'),
            $this->body,
        );
    }
}
