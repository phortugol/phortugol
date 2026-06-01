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
trait EvaluatesConjunction
{
    protected function conjunction(): Node
    {
        $left = $this->negation();

        while ($this->stream->check(TokenType::E)) {
            $operator = $this->stream->advance();
            $left = new BinaryNode($left, $operator->type, $this->negation());
        }

        return $left;
    }
}
