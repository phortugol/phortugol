<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Nodes;

use Phortugol\Contracts\Node;

final readonly class ReturnNode implements Node
{
    public function __construct(
        public Node $value,
    ) {
    }
}
