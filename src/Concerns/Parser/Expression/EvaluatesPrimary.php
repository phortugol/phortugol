<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser\Expression;

use Phortugol\Contracts\Node;
use Phortugol\Enums\TokenType;
use Phortugol\Exceptions\ParseException;
use Phortugol\Parser\Fluent\Syntax;
use Phortugol\Parser\Nodes\ArrayAccessNode;
use Phortugol\Parser\Nodes\CallNode;
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
        if ($this->stream->checkAny([TokenType::INTEGER_LITERAL, TokenType::REAL_LITERAL, TokenType::STRING_LITERAL])) {
            return Syntax::fromToken($this->stream->advance());
        }

        if ($this->stream->check(TokenType::IDENTIFIER)) {
            $token = $this->stream->advance();

            if ($this->stream->match(TokenType::LEFT_BRACKET)) {
                $index = $this->parse();
                $this->stream->consume(type: TokenType::RIGHT_BRACKET, message: 'Expected "]" after index');

                return new ArrayAccessNode($token->lexeme, $index);
            }

            if ($this->stream->match(TokenType::LEFT_PAREN)) {
                $arguments = [];

                if (! $this->stream->check(TokenType::RIGHT_PAREN)) {
                    $arguments[] = $this->parse();

                    while ($this->stream->match(TokenType::COMMA)) {
                        $arguments[] = $this->parse();
                    }
                }

                $this->stream->consume(type: TokenType::RIGHT_PAREN, message: 'Expected ")" after arguments');

                return new CallNode($token->lexeme, $arguments);
            }

            return new VariableNode($token->lexeme);
        }

        if ($this->stream->check(TokenType::VERDADEIRO)) {
            $this->stream->advance();

            return Syntax::verdadeiro();
        }

        if ($this->stream->check(TokenType::FALSO)) {
            $this->stream->advance();

            return Syntax::falso();
        }

        if ($this->stream->match(TokenType::LEFT_PAREN)) {
            $expression = $this->parse();
            $this->stream->consume(type: TokenType::RIGHT_PAREN, message: 'Expected ")" after expression');

            return $expression;
        }

        throw new ParseException("Unexpected token '{$this->stream->peek->lexeme}' at line {$this->stream->peek->line}");
    }
}
