<?php

declare(strict_types = 1);

namespace Phortugol\Contracts\Parser;

use Phortugol\Enums\TokenType;
use Phortugol\Lexer\Token;

interface ConsumesTokens
{
    public Token $peek { get; }

    public Token $previous { get; }

    public bool $isAtEnd { get; }

    public function advance(): Token;

    public function consume(TokenType $type, string $message): Token;

    public function match(TokenType $type): bool;

    public function check(TokenType $type): bool;

    /**
     * @param list<TokenType> $types
     */
    public function checkAny(array $types): bool;
}
