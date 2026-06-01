<?php

declare(strict_types = 1);

namespace Phortugol\Parser;

use Phortugol\Concerns\Parser\UnwrapsTokenValue;
use Phortugol\Contracts\Node;

final readonly class Expression
{
    use UnwrapsTokenValue;

    public function __construct(
        private TokenStream $stream,
    ) {
    }

    public function parse(): Node
    {
        return $this->disjunction();
    }
}
