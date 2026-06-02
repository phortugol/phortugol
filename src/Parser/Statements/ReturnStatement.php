<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Statements;

use Phortugol\Contracts\Parser\Statement;
use Phortugol\Parser\Nodes\ReturnNode;
use Phortugol\Parser\Parser;

final class ReturnStatement implements Statement
{
    public function __invoke(Parser $parser): ReturnNode
    {
        $parser->stream->advance();

        return new ReturnNode($parser->expression());
    }
}
