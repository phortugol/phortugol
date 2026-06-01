<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser\Expression;

use Phortugol\Contracts\Node;
use Phortugol\Enums\TokenType;
use Phortugol\Parser\Nodes\UnaryNode;
use Phortugol\Parser\TokenStream;

/**
 * @phpstan-property TokenStream $stream
 */
trait EvaluatesNegation
{
    protected function negation(): Node
    {
        if ($this->stream->check(TokenType::NAO)) {
            $operator = $this->stream->advance();

            return new UnaryNode($operator->type, $this->negation());
        }

        return $this->comparison();
    }
}
