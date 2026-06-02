<?php

declare(strict_types = 1);

use Phortugol\Console\Kernel;
use Phortugol\Console\Presenters\FakePresenter;
use Phortugol\Exceptions\RuntimeException;
use Phortugol\Interpreter\Runner;
use Phortugol\Runtime\FakeRuntime;

it('creates a Kernel with defaults', function (): void {
    expect(Kernel::configure()->create())->toBeInstanceOf(Kernel::class);
});

it('throws when withRunner and withPresenter are combined', function (): void {
    Kernel::configure()
        ->withRunner(Runner::create(new FakeRuntime()))
        ->withPresenter(new FakePresenter())
        ->withCommands()
        ->create();
})->throws(RuntimeException::class);
