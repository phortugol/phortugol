<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser;

use Phortugol\Enums\TokenType;
use Phortugol\Lexer\Token;

trait NavigatesTokens
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

    public function advance(): Token
    {
        if (! $this->isAtEnd) {
            $this->current++;
        }

        return $this->previous;
    }
}
