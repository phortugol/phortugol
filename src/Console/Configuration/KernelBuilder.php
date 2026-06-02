<?php

declare(strict_types = 1);

namespace Phortugol\Console\Configuration;

use Phortugol\Console\Commands\RunCommand;
use Phortugol\Console\Kernel;
use Phortugol\Console\Presenters\TermwindPresenter;
use Phortugol\Contracts\Console\Presenter;
use Phortugol\Exceptions\RuntimeException;
use Phortugol\Interpreter\Runner;
use Phortugol\Runtime\TerminalRuntime;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;

final class KernelBuilder
{
    /**
     * @var list<string>
     */
    private array $extensions = [];

    /**
     * @var list<Command>
     */
    private array $commands = [];

    private Runner | null $runner = null;

    private Presenter | null $presenter = null;

    private Application $app;

    public function __construct(
        Application | null $app = null,
    ) {
        $this->app = $app ?? new Application(name: 'Phortugol');
    }

    /**
     * @param list<string> $extensions
     */
    public function withExtensions(array $extensions = ['alg', 'por', 'portugol']): KernelBuilder
    {
        $this->extensions = $extensions;

        return $this;
    }

    /**
     * @param list<Command> $commands
     */
    public function withCommands(array $commands = []): KernelBuilder
    {
        if ($this->runner !== null && $this->presenter !== null) {
            throw new RuntimeException('Cannot combine withRunner() and withPresenter(): the presenter is only used to build the default TerminalRuntime. Pass the presenter directly to your runtime instead.');
        }

        $presenter = $this->presenter ?? new TermwindPresenter();
        $runner = $this->runner       ?? Runner::create(new TerminalRuntime($presenter));

        $this->commands = [new RunCommand($runner, $this->extensions), ...$commands];

        return $this;
    }

    public function withRunner(Runner $runner): KernelBuilder
    {
        $this->runner = $runner;

        return $this;
    }

    public function withPresenter(Presenter $presenter = new TermwindPresenter()): KernelBuilder
    {
        $this->presenter = $presenter;

        return $this;
    }

    public function create(): Kernel
    {
        $app = $this->app;

        foreach ($this->commands as $command) {
            $app->addCommand($command);
        }

        $app->setDefaultCommand(commandName: 'run', isSingleCommand: true);
        $app->setAutoExit(boolean: false);

        return new Kernel($app);
    }
}
