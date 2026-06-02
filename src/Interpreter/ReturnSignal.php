<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter;

use Exception;

/**
 * Thrown by ReturnExecutor; caught by CallExecutor to carry the return value.
 */
final class ReturnSignal extends Exception
{
    public function __construct(
        public readonly mixed $value = null,
    ) {
        parent::__construct();
    }
}
