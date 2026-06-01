<?php

declare(strict_types = 1);

namespace Phortugol\Console\Commands;

use Phortugol\Exceptions\LexerException;
use Phortugol\Exceptions\ParseException;
use Phortugol\Exceptions\RuntimeException;
use Phortugol\Interpreter\Runner;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'run', description: 'Execute a Portugol file')]
final class RunCommand extends Command
{
    /**
     * @param list<string> $extensions
     */
    public function __construct(
        private readonly Runner $runner,
        private readonly array $extensions = ['alg', 'por', 'portugol'],
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'The Portugol source file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $file = (string) $input->getArgument('file');

        if (! file_exists($file)) {
            $io->error("File not found: {$file}");

            return Command::FAILURE;
        }

        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (! in_array($extension, $this->extensions, strict: true)) {
            $supported = implode(', ', array_map(fn (string $ext) => ".{$ext}", $this->extensions));
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
            $this->runner->run($source);
            $io->success('Done');

            return Command::SUCCESS;
        } catch (LexerException | ParseException | RuntimeException $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}
