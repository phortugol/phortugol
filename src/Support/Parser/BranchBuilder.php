<?php

declare(strict_types = 1);

namespace Phortugol\Support\Parser;

use Phortugol\Concerns\Support\Parser\CanBeFalse;
use Phortugol\Concerns\Support\Parser\CanBeLiteral;
use Phortugol\Concerns\Support\Parser\CanBeTrue;
use Phortugol\Concerns\Support\Parser\CanBeVariable;
use Phortugol\Concerns\Support\Parser\CanWhen;
use Phortugol\Concerns\Support\Parser\HasOtherwise;
use Phortugol\Concerns\Support\Parser\HasThen;
use Phortugol\Contracts\Node;
use Phortugol\Parser\Nodes\IfNode;

final class BranchBuilder
{
    use CanBeTrue;
    use CanBeFalse;
    use CanBeLiteral;
    use CanBeVariable;
    use CanWhen;
    use HasThen;
    use HasOtherwise;

    private Node | null $condition = null;

    /**
     * @var list<Node>
     */
    private array $thenBranch = [];

    /**
     * @var list<Node>|null
     */
    private array | null $elseBranch = null;

    public function __construct(
        Node | int | float | string | bool | null $condition = null,
    ) {
        if ($condition !== null) {
            $this->condition = $this->evaluate($condition);
        }
    }

    public function build(): IfNode
    {
        return new IfNode(
            $this->condition ?? throw new \LogicException('Branch condition not set — call when(), true(), false(), literal(), or variable() before build()'),
            $this->thenBranch,
            $this->elseBranch,
        );
    }
}
