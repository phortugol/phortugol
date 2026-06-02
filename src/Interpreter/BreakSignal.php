<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter;

use Exception;

/**
 * Thrown by BreakExecutor; caught by loop executors to exit early.
 */
final class BreakSignal extends Exception
{
}
