<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser\Tokens;

use Phortugol\Enums\TokenType;

trait MatchesOptional
{
    public function match(TokenType $type): bool
    {
        if (! $this->check($type)) {
            return false;
        }

        $this->advance();

        return true;
    }
}
