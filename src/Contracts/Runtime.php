<?php

declare(strict_types = 1);

namespace Phortugol\Contracts;

interface Runtime
{
    public function write(string $text): void;

    public function read(): string;
}
