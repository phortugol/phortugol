<?php

declare(strict_types = 1);

namespace Phortugol\Lexer;

use Phortugol\Enums\TokenType;

final class Tokenizer
{
    private readonly Scanner $scanner;

    /**
     * @var list<Token>
     */
    private array $tokens = [];

    public function __construct(
        string $source,
    ) {
        $this->scanner = new Scanner($source);
    }

    /**
     * @return list<Token>
     */
    public function tokenize(): array
    {
        while (! $this->scanner->isAtEnd) {
            $this->scanner->markStart();

            $scanned = $this->scanner->scan();

            if ($scanned !== null) {
                $this->addToken(...$scanned);
            }
        }

        $this->tokens[] = new Token(TokenType::EOF, '', $this->scanner->line);

        return $this->tokens;
    }

    private function addToken(TokenType $type, string | int | float | bool | null $value = null): void
    {
        $this->tokens[] = new Token($type, $this->scanner->slice, $this->scanner->line, $value);
    }
}
