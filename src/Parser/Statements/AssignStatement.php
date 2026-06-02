<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Statements;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\Parser\Statement;
use Phortugol\Enums\TokenType;
use Phortugol\Parser\Nodes\ArrayAssignNode;
use Phortugol\Parser\Nodes\AssignNode;
use Phortugol\Parser\Parser;
use Phortugol\Parser\TokenStream;

final class AssignStatement implements Statement
{
    public function parse(TokenStream $stream, Parser $parser): Node
    {
        $name = $stream->consume(type: TokenType::IDENTIFIER, message: 'Expected variable name');

        if ($stream->match(TokenType::LEFT_BRACKET)) {
            $index = $parser->expression();
            $stream->consume(type: TokenType::RIGHT_BRACKET, message: 'Expected "]" after index');
            $stream->consume(type: TokenType::ASSIGN, message: 'Expected "<-" or ":=" after index');

            return new ArrayAssignNode($name->lexeme, $index, $parser->expression());
        }

        $stream->consume(type: TokenType::ASSIGN, message: 'Expected "<-" or ":=" after variable name');

        return new AssignNode($name->lexeme, $parser->expression());
    }
}
