<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser;

use Phortugol\Enums\TokenType;
use Phortugol\Exceptions\ParseException;
use Phortugol\Lexer\Token;

trait ConsumesExpectedToken
{
    public function consume(TokenType $type, string $message): Token
    {
        if ($this->check($type)) {
            return $this->advance();
        }

        throw new ParseException("{$message} at line {$this->peek->line}");
    }
}
