<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Statements;

use Phortugol\Contracts\Parser\Statement;
use Phortugol\Enums\TokenType;
use Phortugol\Parser\Parser;
use Phortugol\Parser\Nodes\AssignNode;
use Phortugol\Parser\TokenStream;

final class AssignStatement implements Statement
{
    public function parse(TokenStream $stream, Parser $parser): AssignNode
    {
        $name = $stream->consume(TokenType::IDENTIFIER, 'Expected variable name');

        $stream->consume(TokenType::ASSIGN, 'Expected "<-" or ":=" after variable name');

        return new AssignNode($name->lexeme, $parser->expression());
    }
}
