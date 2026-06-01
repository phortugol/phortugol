<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Support\Parser;

use Phortugol\Contracts\Node;

/**
 * @phpstan-property list<Node> $body
 */
trait HasBody
{
    public function body(Node ...$statements): static
    {
        $this->body = array_values($statements);

        return $this;
    }
}
