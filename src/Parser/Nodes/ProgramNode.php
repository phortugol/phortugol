<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Nodes;

use Phortugol\Contracts\Node;

final readonly class ProgramNode implements Node
{
    /**
     * @param list<Node> $statements
     */
    public function __construct(
        public array $statements,
    ) {
    }
}
