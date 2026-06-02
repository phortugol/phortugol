<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Nodes;

use Phortugol\Contracts\Node;

final readonly class ProcedureNode implements Node
{
    /**
     * @param list<string> $parameters
     * @param list<Node>   $body
     */
    public function __construct(
        public string $name,
        public array $parameters,
        public array $body,
    ) {
    }
}
