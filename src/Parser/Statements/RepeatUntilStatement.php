<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Statements;

use Phortugol\Contracts\Parser\Statement;
use Phortugol\Enums\TokenType;
use Phortugol\Parser\Nodes\RepeatUntilNode;
use Phortugol\Parser\Parser;
use Phortugol\Parser\TokenStream;

final class RepeatUntilStatement implements Statement
{
    public function parse(TokenStream $stream, Parser $parser): RepeatUntilNode
    {
        $stream->advance();

        $body = $parser->block([TokenType::ATE]);

        $stream->consume(type: TokenType::ATE, message: 'Expected "ate" after repita body');
        $condition = $parser->expression();

        return new RepeatUntilNode($body, $condition);
    }
}
