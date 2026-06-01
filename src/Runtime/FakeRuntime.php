<?php

declare(strict_types = 1);

namespace Phortugol\Runtime;

use Phortugol\Contracts\Runtime;
use Phortugol\Exceptions\RuntimeException;

final class FakeRuntime implements Runtime
{
    /**
     * @var list<string>
     */
    public array $output = [];

    private int $inputIndex = 0;

    /**
     * @param list<string> $inputs
     */
    public function __construct(
        private readonly array $inputs = [],
    ) {
    }

    public function write(string $text): void
    {
        $this->output[] = $text;
    }

    public function read(): string
    {
        if ($this->inputIndex >= count($this->inputs)) {
            throw new RuntimeException('No more inputs available');
        }

        return $this->inputs[$this->inputIndex++];
    }
}
