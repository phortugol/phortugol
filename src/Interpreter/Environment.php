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

    public function __construct(
        private readonly Environment | null $parent = null,
    ) {
    }

    public function define(string $name, mixed $value): void
    {
        $this->variables[strtolower($name)] = $value;
    }

    public function set(string $name, mixed $value): void
    {
        $key = strtolower($name);

        if (array_key_exists($key, $this->variables)) {
            $this->variables[$key] = $value;

            return;
        }

        if ($this->parent?->has($name)) {
            $this->parent->set($name, $value);

            return;
        }

        $this->variables[$key] = $value;
    }

    public function get(string $name): mixed
    {
        $key = strtolower($name);

        if (array_key_exists($key, $this->variables)) {
            return $this->variables[$key];
        }

        if ($this->parent !== null) {
            return $this->parent->get($name);
        }

        throw new RuntimeException("Undefined variable: {$name}");
    }

    public function has(string $name): bool
    {
        if (array_key_exists(strtolower($name), $this->variables)) {
            return true;
        }

        return $this->parent?->has($name) ?? false;
    }
}
