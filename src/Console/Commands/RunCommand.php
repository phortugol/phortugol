<?php

declare(strict_types = 1);

namespace Phortugol\Console\Commands;

use Phortugol\Console\Kernel;
use Phortugol\Exceptions\LexerException;
use Phortugol\Exceptions\ParseException;
use Phortugol\Exceptions\RuntimeException;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'run', description: 'Execute a Portugol file')]
final readonly class RunCommand
{
    public function __construct(
        private Kernel $kernel,
    ) {
    }

    public function __invoke(
        #[Argument('The Portugol source file')] string $file,
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $io = new SymfonyStyle($input, $output);

        if (! file_exists($file)) {
            $io->error("File not found: {$file}");

            return Command::FAILURE;
        }

        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (! in_array($extension, $this->kernel->extensions, strict: true)) {
            $supported = implode(', ', array_map(fn (string $ext) => ".{$ext}", $this->kernel->extensions));
            $io->error("Unsupported file type '.{$extension}'. Supported extensions: {$supported}");

            return Command::FAILURE;
        }

        $source = file_get_contents($file);

        if ($source === false) {
            $io->error("Could not read file: {$file}");

            return Command::FAILURE;
        }

        $io->writeln(basename($file));

        try {
            $this->kernel->runner->run($source);

            $io->success('Done');

            return Command::SUCCESS;
        } catch (LexerException | ParseException | RuntimeException $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}
