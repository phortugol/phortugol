<?php

declare(strict_types = 1);

namespace Phortugol\Contracts;

use Phortugol\Interpreter\Runner;

/**
 * @template-covariant TNode of Node
 */
interface NodeExecutor
{
    public function execute(Node $node, Runner $runner): mixed;
}
