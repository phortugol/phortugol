<?php

declare(strict_types = 1);

namespace Phortugol\Console;

use Phortugol\Console\Configuration\KernelBuilder;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final readonly class Kernel
{
    public function __construct(
        private Application $app,
    ) {
    }

    public static function configure(): KernelBuilder
    {
        return new KernelBuilder()
            ->withExtensions()
            ->withPresenter()
            ->withCommands();
    }

    public function handle(InputInterface $input, OutputInterface | null $output = null): int
    {
        return $this->app->run($input, $output);
    }
}
