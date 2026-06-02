<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Statements;

use Phortugol\Contracts\Parser\Statement;
use Phortugol\Enums\TokenType;
use Phortugol\Parser\Nodes\ReadNode;
use Phortugol\Parser\Parser;

final class ReadStatement implements Statement
{
    public function __invoke(Parser $parser): ReadNode
    {
        $parser->stream->advance();

        $hasParen = $parser->stream->match(TokenType::LEFT_PAREN);
        $identifiers = [$parser->stream->consume(type: TokenType::IDENTIFIER, message: 'Expected variable name')->lexeme];

        while ($parser->stream->match(TokenType::COMMA)) {
            $identifiers[] = $parser->stream->consume(type: TokenType::IDENTIFIER, message: 'Expected variable name')->lexeme;
        }

        if ($hasParen) {
            $parser->stream->consume(type: TokenType::RIGHT_PAREN, message: 'Expected ")" after leia arguments');
        }

        return new ReadNode($identifiers);
    }
}
