<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser\Fluent;

use Phortugol\Contracts\Node;

trait HasPortugueseLoopBody
{
    public function faca(Node ...$statements): static
    {
        return $this->body(...$statements);
    }
}
