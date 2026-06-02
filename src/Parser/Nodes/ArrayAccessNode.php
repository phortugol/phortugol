<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Nodes;

use Phortugol\Contracts\Node;

final readonly class ArrayAccessNode implements Node
{
    public function __construct(
        public string $name,
        public Node $index,
    ) {
    }
}
