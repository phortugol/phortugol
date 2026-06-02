<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Statements;

use Phortugol\Contracts\Parser\Statement;
use Phortugol\Enums\TokenType;
use Phortugol\Parser\Nodes\WriteNode;
use Phortugol\Parser\Parser;

final class WriteStatement implements Statement
{
    public function __invoke(Parser $parser): WriteNode
    {
        $newline = $parser->stream->peek->type === TokenType::ESCREVAL;
        $parser->stream->advance();

        $hasParen = $parser->stream->match(TokenType::LEFT_PAREN);
        $expressions = [$parser->expression()];

        while ($parser->stream->match(TokenType::COMMA)) {
            $expressions[] = $parser->expression();
        }

        if ($hasParen) {
            $parser->stream->consume(type: TokenType::RIGHT_PAREN, message: 'Expected ")" after escreva arguments');
        }

        return new WriteNode($expressions, $newline);
    }
}
