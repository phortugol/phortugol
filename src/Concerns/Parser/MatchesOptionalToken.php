<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser;

use Phortugol\Enums\TokenType;

trait MatchesOptionalToken
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
