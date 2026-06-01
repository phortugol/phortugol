<?php

declare(strict_types=1);

namespace Phortugol\Contracts;

use Phortugol\Interpreter\Runner;

interface NodeExecutor
{
    public function execute(Node $node, Runner $runner): void;
}
