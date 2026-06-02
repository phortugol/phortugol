<?php

declare(strict_types = 1);

namespace Phortugol\Console;

use Phortugol\Console\Configuration\KernelBuilder;
use Phortugol\Interpreter\Runner;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Kernel
{
    public Runner $runner {
        set {
            $this->runner = $value;
        }
    }

    /**
     * @var list<string>
     */
    public array $extensions {
        set {
            $this->extensions = $value;
        }
    }

    public function __construct(
        private readonly Application $app,
    ) {
    }

    public static function configure(): KernelBuilder
    {
        return new KernelBuilder(new self(new Application(name: 'Phortugol')))
            ->withExtensions()
            ->withPresenter()
            ->withRunner()
            ->withCommands();
    }

    public function addCommand(callable | Command $command): void
    {
        $this->app->addCommand($command);
    }

    public function boot(): void
    {
        $this->app->setDefaultCommand(commandName: 'run', isSingleCommand: true);
        $this->app->setAutoExit(boolean: false);
    }

    public function handle(InputInterface $input, OutputInterface | null $output = null): int
    {
        return $this->app->run($input, $output);
    }
}
