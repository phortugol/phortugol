<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Nodes;

use Phortugol\Contracts\Node;
use Phortugol\Enums\TokenType;

final readonly class UnaryNode implements Node
{
    public function __construct(
        public TokenType $operator,
        public Node $right,
    ) {
    }
}
