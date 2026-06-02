<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Statements;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\Parser\Statement;
use Phortugol\Enums\TokenType;
use Phortugol\Parser\Nodes\ForNode;
use Phortugol\Parser\Parser;

final class ForStatement implements Statement
{
    public function __invoke(Parser $parser): ForNode
    {
        $parser->stream->advance();
        $variable = $parser->stream->consume(type: TokenType::IDENTIFIER, message: 'Expected variable name after "para"');
        $parser->stream->consume(type: TokenType::DE, message: 'Expected "de" after variable');
        $from = $parser->expression();
        $parser->stream->consume(type: TokenType::ATE, message: 'Expected "ate" after start expression');
        $to = $parser->expression();

        $step = $this->parseStep($parser);

        $parser->stream->consume(type: TokenType::FACA, message: 'Expected "faca"');

        $body = $parser->block([TokenType::FIMPARA]);

        $parser->stream->consume(type: TokenType::FIMPARA, message: 'Expected "fimpara"');

        return new ForNode($variable->lexeme, $from, $to, $step, $body);
    }

    private function parseStep(Parser $parser): Node | null
    {
        if (! $parser->stream->match(TokenType::PASSO)) {
            return null;
        }

        return $parser->expression();
    }
}
