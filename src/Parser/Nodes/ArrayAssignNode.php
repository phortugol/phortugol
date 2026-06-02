<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Nodes;

use Phortugol\Contracts\Node;

final readonly class ArrayAssignNode implements Node
{
    public function __construct(
        public string $name,
        public Node $index,
        public Node $value,
    ) {
    }
}
