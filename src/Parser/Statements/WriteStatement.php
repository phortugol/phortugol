<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Statements;

use Phortugol\Contracts\Parser\Statement;
use Phortugol\Enums\TokenType;
use Phortugol\Parser\Nodes\WriteNode;
use Phortugol\Parser\Parser;
use Phortugol\Parser\TokenStream;

final class WriteStatement implements Statement
{
    public function parse(TokenStream $stream, Parser $parser): WriteNode
    {
        $newline = $stream->peek->type === TokenType::ESCREVAL;
        $stream->advance();

        $hasParen = $stream->match(TokenType::LEFT_PAREN);
        $expressions = [$parser->expression()];

        while ($stream->match(TokenType::COMMA)) {
            $expressions[] = $parser->expression();
        }

        if ($hasParen) {
            $stream->consume(type: TokenType::RIGHT_PAREN, message: 'Expected ")" after escreva arguments');
        }

        return new WriteNode($expressions, $newline);
    }
}
