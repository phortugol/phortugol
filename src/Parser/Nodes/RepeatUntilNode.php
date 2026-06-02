<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Nodes;

use Phortugol\Contracts\Node;

final readonly class RepeatUntilNode implements Node
{
    /**
     * @param list<Node> $body
     */
    public function __construct(
        public array $body,
        public Node $condition,
    ) {
    }
}
