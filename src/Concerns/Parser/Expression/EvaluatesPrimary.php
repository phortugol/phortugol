<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser\Expression;

use Phortugol\Contracts\Node;
use Phortugol\Enums\TokenType;
use Phortugol\Exceptions\ParseException;
use Phortugol\Parser\Nodes\LiteralNode;
use Phortugol\Parser\Nodes\VariableNode;
use Phortugol\Parser\TokenStream;

/**
 * @phpstan-method Node parse()
 * @phpstan-property TokenStream $stream
 */
trait EvaluatesPrimary
{
    protected function primary(): Node
    {
        if ($this->stream->check(TokenType::INTEGER_LITERAL)) {
            return new LiteralNode($this->tokenValue($this->stream->advance()));
        }

        if ($this->stream->check(TokenType::REAL_LITERAL)) {
            return new LiteralNode($this->tokenValue($this->stream->advance()));
        }

        if ($this->stream->check(TokenType::STRING_LITERAL)) {
            return new LiteralNode($this->tokenValue($this->stream->advance()));
        }

        if ($this->stream->check(TokenType::VERDADEIRO)) {
            $this->stream->advance();

            return new LiteralNode(true);
        }

        if ($this->stream->check(TokenType::FALSO)) {
            $this->stream->advance();

            return new LiteralNode(false);
        }

        if ($this->stream->check(TokenType::IDENTIFIER)) {
            $token = $this->stream->advance();

            return new VariableNode($token->lexeme);
        }

        if ($this->stream->match(TokenType::LEFT_PAREN)) {
            $expr = $this->parse();
            $this->stream->consume(TokenType::RIGHT_PAREN, 'Expected ")" after expression');

            return $expr;
        }

        throw new ParseException("Unexpected token '{$this->stream->peek->lexeme}' at line {$this->stream->peek->line}");
    }
}
