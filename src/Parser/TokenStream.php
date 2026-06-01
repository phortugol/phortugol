<?php

declare(strict_types = 1);

namespace Phortugol\Parser;

use Phortugol\Enums\TokenType;
use Phortugol\Exceptions\ParseException;
use Phortugol\Lexer\Token;

final class TokenStream
{
    private int $current = 0;

    public Token $peek {
        get => $this->tokens[$this->current];
    }

    public Token $previous {
        get => $this->tokens[$this->current - 1];
    }

    public bool $isAtEnd {
        get => $this->peek->type === TokenType::EOF;
    }

    /**
     * @param list<Token> $tokens
     */
    public function __construct(
        private readonly array $tokens,
    ) {
    }

    public function advance(): Token
    {
        if (! $this->isAtEnd) {
            $this->current++;
        }

        return $this->previous;
    }

    public function consume(TokenType $type, string $message): Token
    {
        if ($this->check($type)) {
            return $this->advance();
        }

        throw new ParseException("{$message} at line {$this->peek->line}");
    }

    public function match(TokenType $type): bool
    {
        if (! $this->check($type)) {
            return false;
        }

        $this->advance();

        return true;
    }

    public function check(TokenType $type): bool
    {
        return ! $this->isAtEnd && $this->peek->type === $type;
    }

    /**
     * @param list<TokenType> $types
     */
    public function checkAny(array $types): bool
    {
        foreach ($types as $type) {
            if ($this->check($type)) {
                return true;
            }
        }

        return false;
    }
}
