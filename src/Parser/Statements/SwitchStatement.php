<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Statements;

use Phortugol\Contracts\Parser\Statement;
use Phortugol\Enums\TokenType;
use Phortugol\Parser\Nodes\CaseNode;
use Phortugol\Parser\Nodes\SwitchNode;
use Phortugol\Parser\Parser;

final class SwitchStatement implements Statement
{
    public function __invoke(Parser $parser): SwitchNode
    {
        $parser->stream->advance();
        $target = $parser->expression();

        $cases = [];

        while ($parser->stream->check(TokenType::CASO)) {
            $parser->stream->advance();
            $value = $parser->expression();
            $body = $parser->block([TokenType::CASO, TokenType::OUTROCASO, TokenType::FIMCASO]);
            $cases[] = new CaseNode($value, $body);
        }

        $otherwise = null;

        if ($parser->stream->match(TokenType::OUTROCASO)) {
            $otherwise = $parser->block([TokenType::FIMCASO]);
        }

        $parser->stream->consume(type: TokenType::FIMCASO, message: 'Expected "fimcaso"');

        return new SwitchNode($target, $cases, $otherwise);
    }
}
