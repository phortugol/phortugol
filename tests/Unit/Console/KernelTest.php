<?php

declare(strict_types = 1);

use Phortugol\Console\Kernel;
use Phortugol\Console\Presenters\FakePresenter;

function algFile(string $source): string
{
    $path = sys_get_temp_dir() . '/phortugol_test_' . uniqid() . '.alg';
    file_put_contents($path, $source);

    return $path;
}

// ---------------------------------------------------------------------------
// Argument validation
// ---------------------------------------------------------------------------

it('returns 1 when no file argument is given', function (): void {
    $presenter = new FakePresenter();

    expect(new Kernel($presenter)->handle(['/usr/bin/phortugol']))->toBe(1);
});

it('shows usage error when no file argument is given', function (): void {
    $presenter = new FakePresenter();
    new Kernel($presenter)->handle(['/usr/bin/phortugol']);

    expect($presenter->errors)->toBe(['Usage: phortugol <file.alg|file.por|file.portugol>']);
});

it('returns 1 when file does not exist', function (): void {
    $presenter = new FakePresenter();

    expect(new Kernel($presenter)->handle(['/usr/bin/phortugol', '/nonexistent/file.alg']))->toBe(1);
});

it('shows error when file does not exist', function (): void {
    $presenter = new FakePresenter();
    new Kernel($presenter)->handle(['/usr/bin/phortugol', '/nonexistent/file.alg']);

    expect($presenter->errors)->toBe(['File not found: /nonexistent/file.alg']);
});

// ---------------------------------------------------------------------------
// Extension validation
// ---------------------------------------------------------------------------

it('returns 1 when file extension is not supported', function (): void {
    $path = sys_get_temp_dir() . '/phortugol_test_' . uniqid() . '.txt';
    file_put_contents($path, '');

    $result = new Kernel(new FakePresenter())->handle(['/usr/bin/phortugol', $path]);
    unlink($path);

    expect($result)->toBe(1);
});

it('shows warning and error when file extension is not supported', function (): void {
    $path = sys_get_temp_dir() . '/phortugol_test_' . uniqid() . '.txt';
    file_put_contents($path, '');

    $presenter = new FakePresenter();
    new Kernel($presenter)->handle(['/usr/bin/phortugol', $path]);
    unlink($path);

    expect($presenter->warnings)->toBe(['Unsupported file type: .txt'])
        ->and($presenter->errors)->toBe(['Supported extensions: .alg, .por, .portugol']);
});

// ---------------------------------------------------------------------------
// Successful execution
// ---------------------------------------------------------------------------

it('returns 0 on successful execution', function (): void {
    $path = algFile("algoritmo \"teste\"\ninicio\nfimalgoritmo");

    $result = new Kernel(new FakePresenter())->handle(['/usr/bin/phortugol', $path]);
    unlink($path);

    expect($result)->toBe(0);
});

it('shows filename and done on successful execution', function (): void {
    $path = algFile("algoritmo \"teste\"\ninicio\nfimalgoritmo");
    $filename = basename($path);

    $presenter = new FakePresenter();
    new Kernel($presenter)->handle(['/usr/bin/phortugol', $path]);
    unlink($path);

    expect($presenter->infos)->toBe([$filename, 'Done']);
});

// ---------------------------------------------------------------------------
// Error cases
// ---------------------------------------------------------------------------

it('returns 1 and shows error on lexer error', function (): void {
    $path = algFile("algoritmo \"teste\"\ninicio\n@\nfimalgoritmo");

    $presenter = new FakePresenter();
    $result = new Kernel($presenter)->handle(['/usr/bin/phortugol', $path]);
    unlink($path);

    expect($result)->toBe(1)
        ->and($presenter->errors)->not->toBeEmpty();
});

it('returns 1 and shows error on parse error', function (): void {
    $path = algFile("algoritmo \"teste\"\ninicio\nse\nfimalgoritmo");

    $presenter = new FakePresenter();
    $result = new Kernel($presenter)->handle(['/usr/bin/phortugol', $path]);
    unlink($path);

    expect($result)->toBe(1)
        ->and($presenter->errors)->not->toBeEmpty();
});

it('returns 1 and shows error on runtime error', function (): void {
    $path = algFile("algoritmo \"teste\"\ninicio\nescreva x\nfimalgoritmo");

    $presenter = new FakePresenter();
    $result = new Kernel($presenter)->handle(['/usr/bin/phortugol', $path]);
    unlink($path);

    expect($result)->toBe(1)
        ->and($presenter->errors)->toBe(['Undefined variable: x']);
});
