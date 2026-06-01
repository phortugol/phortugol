<?php

declare(strict_types = 1);

namespace Phortugol\Runtime;

use Phortugol\Contracts\Runtime;

final class TerminalRuntime implements Runtime
{
    public function write(string $text): void
    {
        echo $text;
    }

    public function read(): string
    {
        $line = fgets(STDIN);

        return $line === false ? '' : trim($line);
    }
}
