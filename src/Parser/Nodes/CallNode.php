<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Nodes;

use Phortugol\Contracts\Node;

final readonly class CallNode implements Node
{
    /**
     * @param list<Node> $arguments
     */
    public function __construct(
        public string $name,
        public array $arguments,
    ) {
    }
}
