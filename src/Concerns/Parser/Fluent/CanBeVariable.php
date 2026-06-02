<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser\Fluent;

use Phortugol\Contracts\Node;
use Phortugol\Parser\Nodes\VariableNode;

/**
 * @phpstan-property Node|null $condition
 */
trait CanBeVariable
{
    public function variable(string $name): static
    {
        $this->condition = new VariableNode($name);

        return $this;
    }

    public function variavel(string $name): static
    {
        return $this->variable($name);
    }
}
