<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Statements;

use Phortugol\Contracts\Parser\Statement;
use Phortugol\Parser\Nodes\BreakNode;
use Phortugol\Parser\Parser;
use Phortugol\Parser\TokenStream;

final class BreakStatement implements Statement
{
    public function parse(TokenStream $stream, Parser $parser): BreakNode
    {
        $stream->advance();

        return new BreakNode();
    }
}
