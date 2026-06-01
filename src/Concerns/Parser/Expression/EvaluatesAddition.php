<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser\Expression;

use Phortugol\Contracts\Node;
use Phortugol\Enums\TokenType;
use Phortugol\Parser\Nodes\BinaryNode;
use Phortugol\Parser\TokenStream;

/** @phpstan-property TokenStream $stream */
trait EvaluatesAddition
{
    protected function addition(): Node
    {
        $left = $this->multiplication();

        while ($this->stream->checkAny([TokenType::PLUS, TokenType::MINUS])) {
            $operator = $this->stream->advance();
            $left = new BinaryNode($left, $operator->type, $this->multiplication());
        }

        return $left;
    }
}
