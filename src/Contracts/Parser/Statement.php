<?php

declare(strict_types = 1);

namespace Phortugol\Contracts\Parser;

use Phortugol\Contracts\Node;
use Phortugol\Parser\Parser;
use Phortugol\Parser\TokenStream;

interface Statement
{
    public function parse(TokenStream $stream, Parser $parser): Node;
}
