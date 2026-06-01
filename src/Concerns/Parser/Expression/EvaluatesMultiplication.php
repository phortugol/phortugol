<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser\Expression;

use Phortugol\Contracts\Node;
use Phortugol\Enums\TokenType;
use Phortugol\Parser\Nodes\BinaryNode;
use Phortugol\Parser\TokenStream;

/** @phpstan-property TokenStream $stream */
trait EvaluatesMultiplication
{
    protected function multiplication(): Node
    {
        $left = $this->unary();

        while ($this->stream->checkAny([TokenType::STAR, TokenType::SLASH, TokenType::DIV, TokenType::MOD])) {
            $operator = $this->stream->advance();
            $left = new BinaryNode($left, $operator->type, $this->unary());
        }

        return $left;
    }
}
