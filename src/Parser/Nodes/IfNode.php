<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Nodes;

use Phortugol\Contracts\Node;

final readonly class IfNode implements Node
{
    /**
     * @param list<Node>      $thenBranch
     * @param list<Node>|null $elseBranch
     */
    public function __construct(
        public Node $condition,
        public array $thenBranch,
        public ?array $elseBranch,
    ) {
    }
}
