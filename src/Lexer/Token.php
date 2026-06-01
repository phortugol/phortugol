<?php

declare(strict_types = 1);

namespace Phortugol\Lexer;

use Phortugol\Enums\TokenType;

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
