<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Statements;

use Phortugol\Contracts\Parser\Statement;
use Phortugol\Enums\TokenType;
use Phortugol\Parser\Nodes\IfNode;
use Phortugol\Parser\Parser;

final class IfStatement implements Statement
{
    public function __invoke(Parser $parser): IfNode
    {
        $parser->stream->advance();
        $condition = $parser->expression();
        $parser->stream->consume(type: TokenType::ENTAO, message: 'Expected "entao" after condition');

        $thenBranch = $parser->block([TokenType::SENAO, TokenType::FIMSE]);

        $elseBranch = null;

        if ($parser->stream->match(TokenType::SENAO)) {
            if ($parser->stream->check(TokenType::SE)) {
                $elseBranch = [($this)($parser)];

                return new IfNode($condition, $thenBranch, $elseBranch);
            }

            $elseBranch = $parser->block([TokenType::FIMSE]);
        }

        $parser->stream->consume(type: TokenType::FIMSE, message: 'Expected "fimse"');

        return new IfNode($condition, $thenBranch, $elseBranch);
    }
}
