<?php

declare(strict_types = 1);

namespace Phortugol\Console;

use Phortugol\Console\Commands\RunCommand;
use Phortugol\Console\Presenters\TermwindPresenter;
use Phortugol\Contracts\Console\Presenter;
use Phortugol\Interpreter\Runner;
use Phortugol\Runtime\TerminalRuntime;
use Symfony\Component\Console\Command\Command;

final class Terminal
{
    /**
     * @var list<string>
     */
    private array $extensions = ['alg', 'por', 'portugol'];

    /**
     * @var list<Command>
     */
    private array $commands = [];

    private Runner | null $runner = null;

    private Presenter | null $presenter = null;

    private function __construct()
    {
    }

    public static function configure(): Terminal
    {
        return new Terminal();
    }

    /**
     * @param list<string> $extensions
     */
    public function withExtensions(array $extensions): Terminal
    {
        $this->extensions = $extensions;

        return $this;
    }

    /**
     * @param list<Command> $commands
     */
    public function withCommands(array $commands): Terminal
    {
        $this->commands = $commands;

        return $this;
    }

    public function withRunner(Runner $runner): Terminal
    {
        $this->runner = $runner;

        return $this;
    }

    public function withPresenter(Presenter $presenter): Terminal
    {
        $this->presenter = $presenter;

        return $this;
    }

    public function create(): Kernel
    {
        $presenter = $this->presenter ?? new TermwindPresenter();
        $runner = $this->runner       ?? Runner::create(new TerminalRuntime($presenter));

        return new Kernel([
            new RunCommand($runner, $this->extensions),
            ...$this->commands,
        ]);
    }
}
