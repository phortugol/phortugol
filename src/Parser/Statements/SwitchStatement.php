<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Statements;

use Phortugol\Contracts\Parser\Statement;
use Phortugol\Enums\TokenType;
use Phortugol\Parser\Nodes\CaseNode;
use Phortugol\Parser\Nodes\SwitchNode;
use Phortugol\Parser\Parser;
use Phortugol\Parser\TokenStream;

final class SwitchStatement implements Statement
{
    public function parse(TokenStream $stream, Parser $parser): SwitchNode
    {
        $stream->advance();
        $target = $parser->expression();

        $cases = [];

        while ($stream->check(TokenType::CASO)) {
            $stream->advance();
            $value = $parser->expression();
            $body = $parser->block([TokenType::CASO, TokenType::OUTROCASO, TokenType::FIMCASO]);
            $cases[] = new CaseNode($value, $body);
        }

        $otherwise = null;

        if ($stream->match(TokenType::OUTROCASO)) {
            $otherwise = $parser->block([TokenType::FIMCASO]);
        }

        $stream->consume(type: TokenType::FIMCASO, message: 'Expected "fimcaso"');

        return new SwitchNode($target, $cases, $otherwise);
    }
}
