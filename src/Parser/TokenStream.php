<?php

declare(strict_types = 1);

namespace Phortugol\Parser;

use Phortugol\Concerns\Parser\InteractsWithCursor;
use Phortugol\Contracts\Parser\ConsumesTokens;
use Phortugol\Lexer\Token;

final class TokenStream implements ConsumesTokens
{
    use InteractsWithCursor;

    /**
     * @param list<Token> $tokens
     */
    public function __construct(
        private readonly array $tokens,
    ) {
    }
}
