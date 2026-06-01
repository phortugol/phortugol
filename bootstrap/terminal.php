<?php

declare(strict_types = 1);

use Phortugol\Console\Presenters\TermwindPresenter;
use Phortugol\Console\Terminal;

return Terminal::configure()
    ->withExtensions(['alg', 'por', 'portugol'])
    ->withPresenter(new TermwindPresenter())
    ->create();
