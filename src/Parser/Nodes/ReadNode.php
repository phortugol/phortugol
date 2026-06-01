<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Nodes;

use Phortugol\Contracts\Node;

final readonly class ReadNode implements Node
{
    /**
     * @param list<string> $identifiers
     */
    public function __construct(
        public array $identifiers,
    ) {
    }
}
