<?php

declare(strict_types = 1);

namespace Phortugol\Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

final readonly class Kernel
{
    /**
     * @param list<Command> $commands
     */
    public function __construct(
        private array $commands,
    ) {
    }

    public function handle(InputInterface $input): int
    {
        $app = new Application(name: 'Phortugol');

        foreach ($this->commands as $command) {
            $app->addCommand($command);
        }

        $app->setDefaultCommand('run', true);
        $app->setAutoExit(false);

        return $app->run($input);
    }
}
