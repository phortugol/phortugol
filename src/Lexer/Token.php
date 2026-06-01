<?php

declare(strict_types = 1);

namespace Phortugol\Lexer;

final readonly class Token
{
    public function __construct(
        public TokenType $type,
        public string $lexeme,
        public int $line,
        public string | int | float | bool | null $value = null,
    ) {
    }
}
