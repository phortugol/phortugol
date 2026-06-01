<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser;

use Phortugol\Exceptions\ParseException;
use Phortugol\Lexer\Token;

trait UnwrapsTokenValue
{
    use Expression\EvaluatesDisjunction;
    use Expression\EvaluatesConjunction;
    use Expression\EvaluatesNegation;
    use Expression\EvaluatesComparison;
    use Expression\EvaluatesAddition;
    use Expression\EvaluatesMultiplication;
    use Expression\EvaluatesUnary;
    use Expression\EvaluatesPrimary;

    protected function tokenValue(Token $token): int | float | string | bool
    {
        if ($token->value === null) {
            throw new ParseException("Expected literal value for token '{$token->lexeme}' at line {$token->line}");
        }

        return $token->value;
    }
}
