<?php

declare(strict_types = 1);

use Phortugol\Console\Presenters\FakePresenter;
use Phortugol\Runtime\TerminalRuntime;

it('routes write() through the presenter', function (): void {
    $presenter = new FakePresenter();
    $runtime = new TerminalRuntime($presenter);

    $runtime->write('hello');
    $runtime->write("world\n");

    expect($presenter->outputs)->toBe(['hello', "world\n"]);
});

it('routes read() through the presenter', function (): void {
    $presenter = new FakePresenter(inputs: ['42']);
    $runtime = new TerminalRuntime($presenter);

    expect($runtime->read())->toBe('42');
});
