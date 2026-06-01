<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser;

use Phortugol\Enums\TokenType;

trait ChecksTokenType
{
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
