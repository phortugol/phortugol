<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Nodes;

use Phortugol\Contracts\Node;
use Phortugol\Enums\TokenType;

final readonly class BinaryNode implements Node
{
    public function __construct(
        public Node $left,
        public TokenType $operator,
        public Node $right,
    ) {
    }
}
