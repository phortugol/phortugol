<?php

declare(strict_types = 1);

use Phortugol\Console\Commands\RunCommand;
use Phortugol\Console\Kernel;
use Phortugol\Interpreter\Runner;
use Phortugol\Runtime\FakeRuntime;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

function algFile(string $source): string
{
    $path = sys_get_temp_dir() . '/phortugol_test_' . uniqid() . '.alg';
    file_put_contents($path, $source);

    return $path;
}

function commandTester(): CommandTester
{
    $kernel = new Kernel(new Application());
    $kernel->runner = Runner::create(new FakeRuntime());
    $kernel->extensions = ['alg', 'por', 'portugol'];

    return new CommandTester(new RunCommand($kernel));
}

// ---------------------------------------------------------------------------
// File validation
// ---------------------------------------------------------------------------

it('returns failure when file does not exist', function (): void {
    $tester = commandTester();
    $tester->execute(['file' => '/nonexistent/file.alg']);

    expect($tester->getStatusCode())->toBe(Command::FAILURE);
});

it('shows error when file does not exist', function (): void {
    $tester = commandTester();
    $tester->execute(['file' => '/nonexistent/file.alg']);

    expect($tester->getDisplay())->toContain('File not found: /nonexistent/file.alg');
});

// ---------------------------------------------------------------------------
// Extension validation
// ---------------------------------------------------------------------------

it('returns failure when file extension is not supported', function (): void {
    $path = sys_get_temp_dir() . '/phortugol_test_' . uniqid() . '.txt';
    file_put_contents($path, '');

    $tester = commandTester();
    $tester->execute(['file' => $path]);
    unlink($path);

    expect($tester->getStatusCode())->toBe(Command::FAILURE);
});

it('shows error when file extension is not supported', function (): void {
    $path = sys_get_temp_dir() . '/phortugol_test_' . uniqid() . '.txt';
    file_put_contents($path, '');

    $tester = commandTester();
    $tester->execute(['file' => $path]);
    unlink($path);

    expect($tester->getDisplay())->toContain("Unsupported file type '.txt'");
});

// ---------------------------------------------------------------------------
// Successful execution
// ---------------------------------------------------------------------------

it('returns success on successful execution', function (): void {
    $path = algFile("algoritmo \"teste\"\ninicio\nfimalgoritmo");

    $tester = commandTester();
    $result = $tester->execute(['file' => $path]);
    unlink($path);

    expect($result)->toBe(Command::SUCCESS);
});

it('shows filename and done on successful execution', function (): void {
    $path = algFile("algoritmo \"teste\"\ninicio\nfimalgoritmo");
    $filename = basename($path);

    $tester = commandTester();
    $tester->execute(['file' => $path]);
    unlink($path);

    expect($tester->getDisplay())
        ->toContain($filename)
        ->toContain('Done');
});

// ---------------------------------------------------------------------------
// Error cases
// ---------------------------------------------------------------------------

it('returns failure and shows error on lexer error', function (): void {
    $path = algFile("algoritmo \"teste\"\ninicio\n@\nfimalgoritmo");

    $tester = commandTester();
    $result = $tester->execute(['file' => $path]);
    unlink($path);

    expect($result)->toBe(Command::FAILURE)
        ->and($tester->getDisplay())->not->toBeEmpty();
});

it('returns failure and shows error on parse error', function (): void {
    $path = algFile("algoritmo \"teste\"\ninicio\nse\nfimalgoritmo");

    $tester = commandTester();
    $result = $tester->execute(['file' => $path]);
    unlink($path);

    expect($result)->toBe(Command::FAILURE)
        ->and($tester->getDisplay())->not->toBeEmpty();
});

it('returns failure and shows error on runtime error', function (): void {
    $path = algFile("algoritmo \"teste\"\ninicio\nescreva x\nfimalgoritmo");

    $tester = commandTester();
    $result = $tester->execute(['file' => $path]);
    unlink($path);

    expect($result)->toBe(Command::FAILURE)
        ->and($tester->getDisplay())->toContain('Undefined variable: x');
});
