<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser\Expression;

use Phortugol\Contracts\Node;
use Phortugol\Enums\TokenType;
use Phortugol\Parser\Nodes\BinaryNode;
use Phortugol\Parser\TokenStream;

/**
 * @phpstan-property TokenStream $stream
 */
trait EvaluatesDisjunction
{
    protected function disjunction(): Node
    {
        $left = $this->conjunction();

        while ($this->stream->check(TokenType::OU)) {
            $operator = $this->stream->advance();
            $left = new BinaryNode($left, $operator->type, $this->conjunction());
        }

        return $left;
    }
}
