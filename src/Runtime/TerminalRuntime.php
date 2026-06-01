<?php

declare(strict_types = 1);

namespace Phortugol\Runtime;

use Phortugol\Contracts\Console\Presenter;
use Phortugol\Contracts\Runtime;

final readonly class TerminalRuntime implements Runtime
{
    public function __construct(
        private Presenter $presenter,
    ) {
    }

    public function write(string $text): void
    {
        echo $text;
    }

    public function read(): string
    {
        return $this->presenter->ask();
    }
}
