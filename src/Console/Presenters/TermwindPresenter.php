<?php

declare(strict_types = 1);

namespace Phortugol\Console\Presenters;

use Phortugol\Contracts\Console\Presenter;

use function Termwind\ask;
use function Termwind\render;

final class TermwindPresenter implements Presenter
{
    public function info(string $message): void
    {
        $message = htmlspecialchars($message, ENT_QUOTES);

        render(
            <<<HTML
            <div class="my-1">
                <span class="px-1 bg-blue-500 text-white font-bold">INFO</span>
                <span class="ml-1">{$message}</span>
            </div>
        HTML
        );
    }

    public function error(string $message): void
    {
        $message = htmlspecialchars($message, ENT_QUOTES);

        render(
            <<<HTML
            <div class="my-1">
                <span class="px-1 bg-red-500 text-white font-bold">ERROR</span>
                <span class="ml-1">{$message}</span>
            </div>
        HTML
        );
    }

    public function warning(string $message): void
    {
        $message = htmlspecialchars($message, ENT_QUOTES);

        render(
            <<<HTML
            <div class="my-1">
                <span class="px-1 bg-yellow-500 text-black font-bold">WARN</span>
                <span class="ml-1">{$message}</span>
            </div>
        HTML
        );
    }

    public function ask(): string
    {
        $value = ask('<span class="text-blue-300">›</span> ');

        return $value === null ? '' : (string) $value;
    }
}
