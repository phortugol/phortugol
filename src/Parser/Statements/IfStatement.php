<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Statements;

use Phortugol\Contracts\Parser\Statement;
use Phortugol\Enums\TokenType;
use Phortugol\Parser\Nodes\IfNode;
use Phortugol\Parser\Parser;
use Phortugol\Parser\TokenStream;

final class IfStatement implements Statement
{
    public function parse(TokenStream $stream, Parser $parser): IfNode
    {
        $stream->advance();
        $condition = $parser->expression();
        $stream->consume(type: TokenType::ENTAO, message: 'Expected "entao" after condition');

        $thenBranch = $parser->block([TokenType::SENAO, TokenType::FIMSE]);

        $elseBranch = null;

        if ($stream->match(TokenType::SENAO)) {
            $elseBranch = $parser->block([TokenType::FIMSE]);
        }

        $stream->consume(type: TokenType::FIMSE, message: 'Expected "fimse"');

        return new IfNode($condition, $thenBranch, $elseBranch);
    }
}
