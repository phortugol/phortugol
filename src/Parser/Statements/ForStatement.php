<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Statements;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\Parser\Statement;
use Phortugol\Enums\TokenType;
use Phortugol\Parser\Nodes\ForNode;
use Phortugol\Parser\Parser;
use Phortugol\Parser\TokenStream;

final class ForStatement implements Statement
{
    public function parse(TokenStream $stream, Parser $parser): ForNode
    {
        $stream->advance();
        $variable = $stream->consume(type: TokenType::IDENTIFIER, message: 'Expected variable name after "para"');
        $stream->consume(type: TokenType::DE, message: 'Expected "de" after variable');
        $from = $parser->expression();
        $stream->consume(type: TokenType::ATE, message: 'Expected "ate" after start expression');
        $to = $parser->expression();

        $step = $this->parseStep($stream, $parser);

        $stream->consume(type: TokenType::FACA, message: 'Expected "faca"');

        $body = $parser->block([TokenType::FIMPARA]);

        $stream->consume(type: TokenType::FIMPARA, message: 'Expected "fimpara"');

        return new ForNode($variable->lexeme, $from, $to, $step, $body);
    }

    private function parseStep(TokenStream $stream, Parser $parser): Node | null
    {
        if (! $stream->match(TokenType::PASSO)) {
            return null;
        }

        return $parser->expression();
    }
}
