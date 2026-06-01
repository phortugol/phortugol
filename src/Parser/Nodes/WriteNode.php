<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Nodes;

use Phortugol\Contracts\Node;

final readonly class WriteNode implements Node
{
    /**
     * @param list<Node> $expressions
     */
    public function __construct(
        public array $expressions,
        public bool $newline,
    ) {
    }
}
