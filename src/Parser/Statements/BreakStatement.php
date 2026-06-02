<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Statements;

use Phortugol\Contracts\Parser\Statement;
use Phortugol\Parser\Nodes\BreakNode;
use Phortugol\Parser\Parser;

final class BreakStatement implements Statement
{
    public function __invoke(Parser $parser): BreakNode
    {
        $parser->stream->advance();

        return new BreakNode();
    }
}
