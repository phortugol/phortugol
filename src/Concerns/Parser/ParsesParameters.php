<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser;

use Phortugol\Enums\TokenType;
use Phortugol\Parser\TokenStream;

trait ParsesParameters
{
    /**
     * @return list<string>
     */
    private function parameters(TokenStream $stream): array
    {
        $stream->consume(type: TokenType::LEFT_PAREN, message: 'Expected "(" after subroutine name');

        $parameters = [];

        if (! $stream->check(TokenType::RIGHT_PAREN)) {
            $parameters[] = $stream->consume(type: TokenType::IDENTIFIER, message: 'Expected parameter name')->lexeme;
            $stream->consume(type: TokenType::COLON, message: 'Expected ":" after parameter name');
            $stream->advance();

            while ($stream->match(TokenType::COMMA)) {
                $parameters[] = $stream->consume(type: TokenType::IDENTIFIER, message: 'Expected parameter name')->lexeme;
                $stream->consume(type: TokenType::COLON, message: 'Expected ":" after parameter name');
                $stream->advance();
            }
        }

        $stream->consume(type: TokenType::RIGHT_PAREN, message: 'Expected ")" after parameters');

        return $parameters;
    }
}
