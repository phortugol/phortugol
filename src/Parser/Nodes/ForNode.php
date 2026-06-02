<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Nodes;

use Phortugol\Contracts\Node;

final readonly class ForNode implements Node
{
    /**
     * @param list<Node> $body
     */
    public function __construct(
        public string $variable,
        public Node $from,
        public Node $to,
        public Node | null $step,
        public array $body,
    ) {
    }
}
