<?php

declare(strict_types = 1);

namespace Phortugol\Contracts\Console;

interface Presenter
{
    public function write(string $text): void;

    public function info(string $message): void;

    public function error(string $message): void;

    public function warning(string $message): void;

    public function ask(): string;
}
