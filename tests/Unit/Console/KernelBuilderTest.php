<?php

declare(strict_types = 1);

use Phortugol\Console\Kernel;

it('creates a Kernel with defaults', function (): void {
    expect(Kernel::configure()->create())->toBeInstanceOf(Kernel::class);
});
