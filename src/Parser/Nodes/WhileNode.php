<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Nodes;

use Phortugol\Contracts\Node;

final readonly class WhileNode implements Node
{
    /**
     * @param list<Node> $body
     */
    public function __construct(
        public Node $condition,
        public array $body,
    ) {
    }
}
