<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Statements;

use Phortugol\Contracts\Parser\Statement;
use Phortugol\Enums\TokenType;
use Phortugol\Parser\Parser;
use Phortugol\Parser\Nodes\ReadNode;
use Phortugol\Parser\TokenStream;

final class ReadStatement implements Statement
{
    public function parse(TokenStream $stream, Parser $parser): ReadNode
    {
        $stream->advance();

        $hasParen = $stream->match(TokenType::LEFT_PAREN);
        $identifiers = [$stream->consume(type: TokenType::IDENTIFIER, message: 'Expected variable name')->lexeme];

        while ($stream->match(TokenType::COMMA)) {
            $identifiers[] = $stream->consume(type: TokenType::IDENTIFIER, message: 'Expected variable name')->lexeme;
        }

        if ($hasParen) {
            $stream->consume(type: TokenType::RIGHT_PAREN, message: 'Expected ")" after leia arguments');
        }

        return new ReadNode($identifiers);
    }
}
