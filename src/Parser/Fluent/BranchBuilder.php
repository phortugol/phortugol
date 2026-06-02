<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Fluent;

use Phortugol\Concerns\Parser\Fluent\HasCondition;
use Phortugol\Concerns\Parser\Fluent\HasElseClause;
use Phortugol\Concerns\Parser\Fluent\HasThenClause;
use Phortugol\Contracts\Node;
use Phortugol\Exceptions\RuntimeException;
use Phortugol\Parser\Nodes\IfNode;

final class BranchBuilder
{
    use HasCondition;
    use HasThenClause;
    use HasElseClause;

    private Node | null $condition = null;

    /**
     * @var list<Node>
     */
    private array $thenBranch = [];

    /**
     * @var list<Node>|null
     */
    private array | null $elseBranch = null;

    public static function make(
        Node | int | float | string | bool | null $condition = null,
    ): BranchBuilder {
        return new BranchBuilder($condition);
    }

    public function __construct(
        Node | int | float | string | bool | null $condition = null,
    ) {
        if ($condition !== null) {
            $this->condition = $this->evaluate($condition);
        }
    }

    public function create(): IfNode
    {
        return new IfNode(
            $this->condition ?? throw new RuntimeException('Branch condition not set — call when(), true(), false(), literal(), or variable() before create()'),
            $this->thenBranch,
            $this->elseBranch,
        );
    }
}
