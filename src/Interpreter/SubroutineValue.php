<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter;

use Phortugol\Contracts\Node;

final readonly class SubroutineValue
{
    /**
     * @param list<string> $parameters
     * @param list<Node>   $body
     */
    public function __construct(
        public array $parameters,
        public array $body,
    ) {
    }
}
