<?php

declare(strict_types = 1);

namespace Phortugol\Console\Configuration;

use Phortugol\Console\Commands\RunCommand;
use Phortugol\Console\Kernel;
use Phortugol\Console\Presenters\TermwindPresenter;
use Phortugol\Contracts\Console\Presenter;
use Phortugol\Interpreter\Runner;
use Phortugol\Runtime\TerminalRuntime;

final class KernelBuilder
{
    private Presenter | null $presenter = null;

    public function __construct(
        private readonly Kernel $kernel,
    ) {
    }

    public function withExtensions(): KernelBuilder
    {
        $this->kernel->extensions = ['alg', 'por', 'portugol'];

        return $this;
    }

    public function withRunner(): KernelBuilder
    {
        $this->kernel->runner = Runner::create(new TerminalRuntime($this->presenter ?? new TermwindPresenter()));

        return $this;
    }

    public function withPresenter(Presenter $presenter = new TermwindPresenter()): KernelBuilder
    {
        $this->presenter = $presenter;

        return $this;
    }

    public function withCommands(): KernelBuilder
    {
        $this->kernel->addCommand(new RunCommand($this->kernel));

        return $this;
    }

    public function create(): Kernel
    {
        $this->kernel->boot();

        return $this->kernel;
    }
}
