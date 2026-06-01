<?php

declare(strict_types = 1);

namespace Phortugol\Console\Presenters;

use Phortugol\Contracts\Console\Presenter;
use Phortugol\Exceptions\RuntimeException;

final class FakePresenter implements Presenter
{
    /**
     * @var list<string>
     */
    public array $infos = [];

    /**
     * @var list<string>
     */
    public array $errors = [];

    /**
     * @var list<string>
     */
    public array $warnings = [];

    private int $inputIndex = 0;

    /**
     * @param list<string> $inputs
     */
    public function __construct(
        private readonly array $inputs = [],
    ) {
    }

    public function info(string $message): void
    {
        $this->infos[] = $message;
    }

    public function error(string $message): void
    {
        $this->errors[] = $message;
    }

    public function warning(string $message): void
    {
        $this->warnings[] = $message;
    }

    public function ask(): string
    {
        if ($this->inputIndex >= count($this->inputs)) {
            throw new RuntimeException('No more inputs available');
        }

        return $this->inputs[$this->inputIndex++];
    }
}
