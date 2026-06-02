<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser\Fluent;

use Phortugol\Contracts\Node;

/**
 * @phpstan-property list<Node> $body
 */
trait HasLoopBody
{
    public function body(Node ...$statements): static
    {
        $this->body = array_values($statements);

        return $this;
    }

    public function faca(Node ...$statements): static
    {
        return $this->body(...$statements);
    }
}
