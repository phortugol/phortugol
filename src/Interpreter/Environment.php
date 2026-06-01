<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter;

use Phortugol\Exceptions\RuntimeException;

final class Environment
{
    /**
     * @var array<string, mixed>
     */
    private array $variables = [];

    public function set(string $name, mixed $value): void
    {
        $this->variables[strtolower($name)] = $value;
    }

    public function get(string $name): mixed
    {
        $key = strtolower($name);

        if (! array_key_exists($key, $this->variables)) {
            throw new RuntimeException("Undefined variable: {$name}");
        }

        return $this->variables[$key];
    }

    public function has(string $name): bool
    {
        return array_key_exists(strtolower($name), $this->variables);
    }
}
