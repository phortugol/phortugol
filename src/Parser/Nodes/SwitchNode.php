<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Nodes;

use Phortugol\Contracts\Node;

final readonly class SwitchNode implements Node
{
    /**
     * @param list<CaseNode>  $cases
     * @param list<Node>|null $otherwise
     */
    public function __construct(
        public Node $target,
        public array $cases,
        public array | null $otherwise,
    ) {
    }
}
