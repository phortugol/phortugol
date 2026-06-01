<?php

declare(strict_types = 1);

namespace Phortugol\Console;

use Phortugol\Contracts\Console\Presenter;
use Phortugol\Exceptions\LexerException;
use Phortugol\Exceptions\ParseException;
use Phortugol\Exceptions\RuntimeException;
use Phortugol\Interpreter\Runner;
use Phortugol\Runtime\TerminalRuntime;

final readonly class Kernel
{
    private const array SUPPORTED_EXTENSIONS = ['alg', 'por', 'portugol'];

    public function __construct(
        private Presenter $presenter,
    ) {
    }

    /**
     * @param array<int, string> $argv
     */
    public function handle(array $argv): int
    {
        $file = $argv[1] ?? null;

        if ($file === null) {
            $this->presenter->error('Usage: phortugol <file.alg|file.por|file.portugol>');

            return 1;
        }

        if (! file_exists($file)) {
            $this->presenter->error("File not found: {$file}");

            return 1;
        }

        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (! in_array($extension, self::SUPPORTED_EXTENSIONS, strict: true)) {
            $this->presenter->warning("Unsupported file type: .{$extension}");
            $this->presenter->error('Supported extensions: .alg, .por, .portugol');

            return 1;
        }

        $source = file_get_contents($file);

        if ($source === false) {
            $this->presenter->error("Could not read file: {$file}");

            return 1;
        }

        $this->presenter->info(basename($file));

        try {
            Runner::create(new TerminalRuntime($this->presenter))->run($source);
            $this->presenter->info('Done');

            return 0;
        } catch (LexerException | ParseException | RuntimeException $e) {
            $this->presenter->error($e->getMessage());

            return 1;
        }
    }
}
