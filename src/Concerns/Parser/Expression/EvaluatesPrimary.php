<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser\Expression;

use Phortugol\Contracts\Node;
use Phortugol\Enums\TokenType;
use Phortugol\Exceptions\ParseException;
use Phortugol\Parser\TokenStream;
use Phortugol\Support\Parser\Nodes;

/**
 * @phpstan-method Node parse()
 * @phpstan-property TokenStream $stream
 */
trait EvaluatesPrimary
{
    protected function primary(): Node
    {
        if ($this->stream->checkAny([TokenType::INTEGER_LITERAL, TokenType::REAL_LITERAL, TokenType::STRING_LITERAL, TokenType::IDENTIFIER])) {
            return Nodes::fromToken($this->stream->advance());
        }

        if ($this->stream->check(TokenType::VERDADEIRO)) {
            $this->stream->advance();

            return Nodes::native()->verdadeiro();
        }

        if ($this->stream->check(TokenType::FALSO)) {
            $this->stream->advance();

            return Nodes::native()->falso();
        }

        if ($this->stream->match(TokenType::LEFT_PAREN)) {
            $expr = $this->parse();
            $this->stream->consume(type: TokenType::RIGHT_PAREN, message: 'Expected ")" after expression');

            return $expr;
        }

        throw new ParseException("Unexpected token '{$this->stream->peek->lexeme}' at line {$this->stream->peek->line}");
    }
}
