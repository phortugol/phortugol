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
trait EvaluatesComparison
{
    protected function comparison(): Node
    {
        $left = $this->addition();

        $types = [
            TokenType::EQUAL,
            TokenType::NOT_EQUAL,
            TokenType::LESS,
            TokenType::LESS_EQUAL,
            TokenType::GREATER,
            TokenType::GREATER_EQUAL,
        ];

        while ($this->stream->checkAny($types)) {
            $operator = $this->stream->advance();
            $left = new BinaryNode($left, $operator->type, $this->addition());
        }

        return $left;
    }
}
