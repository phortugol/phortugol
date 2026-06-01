<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Statements;

use Phortugol\Contracts\Parser\Statement;
use Phortugol\Enums\TokenType;
use Phortugol\Parser\Nodes\WhileNode;
use Phortugol\Parser\Parser;
use Phortugol\Parser\TokenStream;

final class WhileStatement implements Statement
{
    public function parse(TokenStream $stream, Parser $parser): WhileNode
    {
        $stream->advance();
        $condition = $parser->expression();
        $stream->consume(type: TokenType::FACA, message: 'Expected "faca" after condition');

        $body = $parser->block([TokenType::FIMENQUANTO]);

        $stream->consume(type: TokenType::FIMENQUANTO, message: 'Expected "fimenquanto"');

        return new WhileNode($condition, $body);
    }
}
