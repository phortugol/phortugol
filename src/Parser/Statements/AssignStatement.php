<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Statements;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\Parser\Statement;
use Phortugol\Enums\TokenType;
use Phortugol\Parser\Nodes\ArrayAssignNode;
use Phortugol\Parser\Nodes\AssignNode;
use Phortugol\Parser\Nodes\CallNode;
use Phortugol\Parser\Parser;

final class AssignStatement implements Statement
{
    public function __invoke(Parser $parser): Node
    {
        $name = $parser->stream->consume(type: TokenType::IDENTIFIER, message: 'Expected variable name');

        if ($parser->stream->match(TokenType::LEFT_BRACKET)) {
            $index = $parser->expression();
            $parser->stream->consume(type: TokenType::RIGHT_BRACKET, message: 'Expected "]" after index');
            $parser->stream->consume(type: TokenType::ASSIGN, message: 'Expected "<-" or ":=" after index');

            return new ArrayAssignNode($name->lexeme, $index, $parser->expression());
        }

        if ($parser->stream->match(TokenType::LEFT_PAREN)) {
            $arguments = [];

            if (! $parser->stream->check(TokenType::RIGHT_PAREN)) {
                $arguments[] = $parser->expression();

                while ($parser->stream->match(TokenType::COMMA)) {
                    $arguments[] = $parser->expression();
                }
            }

            $parser->stream->consume(type: TokenType::RIGHT_PAREN, message: 'Expected ")" after arguments');

            return new CallNode($name->lexeme, $arguments);
        }

        $parser->stream->consume(type: TokenType::ASSIGN, message: 'Expected "<-" or ":=" after variable name');

        return new AssignNode($name->lexeme, $parser->expression());
    }
}
