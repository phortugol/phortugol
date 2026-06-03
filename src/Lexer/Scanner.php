<?php

declare(strict_types = 1);

namespace Phortugol\Lexer;

use Phortugol\Enums\TokenType;
use Phortugol\Exceptions\LexerException;

/**
 * @phpstan-type ScannedToken array{TokenType, scalar | null}
 */
final class Scanner
{
    private int $start = 0;

    private int $current = 0;

    private(set) int $line = 1;

    public bool $isAtEnd {
        get => $this->current >= strlen($this->source);
    }

    public string $peek {
        get => $this->isAtEnd ? "\0" : $this->source[$this->current];
    }

    public string $peekNext {
        get {
            $next = $this->current + 1;

            return $next >= strlen($this->source) ? "\0" : $this->source[$next];
        }
    }

    public string $slice {
        get => substr($this->source, $this->start, $this->current - $this->start);
    }

    /**
     * @var array<string, TokenType>
     */
    private const array KEYWORDS = [
        'algoritmo'       => TokenType::ALGORITMO,
        'ate'             => TokenType::ATE,
        'caractere'       => TokenType::CARACTERE,
        'caso'            => TokenType::CASO,
        'de'              => TokenType::DE,
        'div'             => TokenType::DIV,
        'e'               => TokenType::E,
        'enquanto'        => TokenType::ENQUANTO,
        'entao'           => TokenType::ENTAO,
        'escreva'         => TokenType::ESCREVA,
        'escreval'        => TokenType::ESCREVAL,
        'faca'            => TokenType::FACA,
        'falso'           => TokenType::FALSO,
        'fimalgoritmo'    => TokenType::FIMALGORITMO,
        'fimcaso'         => TokenType::FIMCASO,
        'fimenquanto'     => TokenType::FIMENQUANTO,
        'fimfuncao'       => TokenType::FIMFUNCAO,
        'fimpara'         => TokenType::FIMPARA,
        'fimprocedimento' => TokenType::FIMPROCEDIMENTO,
        'fimse'           => TokenType::FIMSE,
        'funcao'          => TokenType::FUNCAO,
        'inicio'          => TokenType::INICIO,
        'inteiro'         => TokenType::INTEIRO,
        'interrompa'      => TokenType::INTERROMPA,
        'leia'            => TokenType::LEIA,
        'logico'          => TokenType::LOGICO,
        'mod'             => TokenType::MOD,
        'nao'             => TokenType::NAO,
        'ou'              => TokenType::OU,
        'outrocaso'       => TokenType::OUTROCASO,
        'para'            => TokenType::PARA,
        'passo'           => TokenType::PASSO,
        'procedimento'    => TokenType::PROCEDIMENTO,
        'real'            => TokenType::REAL,
        'repita'          => TokenType::REPITA,
        'retorne'         => TokenType::RETORNE,
        'se'              => TokenType::SE,
        'seja'            => TokenType::SEJA,
        'senao'           => TokenType::SENAO,
        'var'             => TokenType::VAR,
        'verdadeiro'      => TokenType::VERDADEIRO,
        'vetor'           => TokenType::VETOR,
    ];

    public function __construct(
        private readonly string $source,
    ) {
    }

    // ── Cursor primitives ────────────────────────────────────────────────────

    public function advance(): string
    {
        $char = $this->source[$this->current++];

        if ($char === "\n") {
            $this->line++;
        }

        return $char;
    }

    public function match(string $char): bool
    {
        if ($this->isAtEnd || $this->source[$this->current] !== $char) {
            return false;
        }

        $this->current++;

        return true;
    }

    public function markStart(): void
    {
        $this->start = $this->current;
    }

    // ── Scanning ─────────────────────────────────────────────────────────────

    /**
     * @return ScannedToken | null
     */
    public function scan(): array | null
    {
        $char = $this->advance();

        return match ($char) {
            '('                   => [TokenType::LEFT_PAREN, null],
            ')'                   => [TokenType::RIGHT_PAREN, null],
            '['                   => [TokenType::LEFT_BRACKET, null],
            ']'                   => [TokenType::RIGHT_BRACKET, null],
            ','                   => [TokenType::COMMA, null],
            '+'                   => [TokenType::PLUS, null],
            '-'                   => [TokenType::MINUS, null],
            '*'                   => [TokenType::STAR, null],
            '%'                   => [TokenType::MOD, null],
            '='                   => [TokenType::EQUAL, null],
            '/'                   => $this->slash(),
            '<'                   => $this->less(),
            '>'                   => $this->greater(),
            ':'                   => $this->colon(),
            '.'                   => $this->dot(),
            '{'                   => $this->blockComment(),
            '"'                   => $this->string(),
            ' ', "\t", "\r", "\n" => null,
            default               => $this->defaultChar($char),
        };
    }

    /**
     * @return array{TokenType, null}|null
     */
    private function slash(): array | null
    {
        if ($this->match('/')) {
            $this->lineComment();

            return null;
        }

        return [TokenType::SLASH, null];
    }

    /**
     * @return array{TokenType, null}
     */
    private function less(): array
    {
        if ($this->match('-')) {
            return [TokenType::ASSIGN, null];
        }

        if ($this->match('=')) {
            return [TokenType::LESS_EQUAL, null];
        }

        if ($this->match('>')) {
            return [TokenType::NOT_EQUAL, null];
        }

        return [TokenType::LESS, null];
    }

    /**
     * @return array{TokenType, null}
     */
    private function greater(): array
    {
        if ($this->match('=')) {
            return [TokenType::GREATER_EQUAL, null];
        }

        return [TokenType::GREATER, null];
    }

    /**
     * @return array{TokenType, null}
     */
    private function colon(): array
    {
        if ($this->match('=')) {
            return [TokenType::ASSIGN, null];
        }

        return [TokenType::COLON, null];
    }

    /**
     * @return array{TokenType, null}
     */
    private function dot(): array
    {
        if ($this->match('.')) {
            return [TokenType::DOTDOT, null];
        }

        return [TokenType::DOT, null];
    }

    private function lineComment(): void
    {
        while ($this->peek !== "\n" && ! $this->isAtEnd) {
            $this->advance();
        }
    }

    private function blockComment(): null
    {
        while ($this->peek !== '}' && ! $this->isAtEnd) {
            $this->advance();
        }

        if ($this->isAtEnd) {
            throw new LexerException("Unterminated block comment at line {$this->line}");
        }

        $this->advance();

        return null;
    }

    /**
     * @return array{TokenType, string}
     */
    private function string(): array
    {
        while ($this->peek !== '"' && ! $this->isAtEnd) {
            $this->advance();
        }

        if ($this->isAtEnd) {
            throw new LexerException("Unterminated string at line {$this->line}");
        }

        $this->advance();

        return [TokenType::STRING_LITERAL, substr($this->slice, 1, -1)];
    }

    /**
     * @return array{TokenType, int|float}
     */
    private function number(): array
    {
        while (ctype_digit($this->peek)) {
            $this->advance();
        }

        if ($this->peek === '.' && ctype_digit($this->peekNext)) {
            $this->advance();

            while (ctype_digit($this->peek)) {
                $this->advance();
            }

            return [TokenType::REAL_LITERAL, (float) $this->slice];
        }

        return [TokenType::INTEGER_LITERAL, (int) $this->slice];
    }

    /**
     * @return array{TokenType, string|bool|null}
     */
    private function identifier(): array
    {
        while (ctype_alnum($this->peek) || $this->peek === '_') {
            $this->advance();
        }

        $lexeme = $this->slice;

        $type = Scanner::KEYWORDS[strtolower($lexeme)] ?? TokenType::IDENTIFIER;

        $value = match ($type) {
            TokenType::VERDADEIRO => true,
            TokenType::FALSO      => false,
            TokenType::IDENTIFIER => $lexeme,
            default               => null,
        };

        return [$type, $value];
    }

    /**
     * @return ScannedToken
     */
    private function defaultChar(string $char): array
    {
        if (ctype_digit($char)) {
            return $this->number();
        }

        if (ctype_alpha($char) || $char === '_') {
            return $this->identifier();
        }

        throw new LexerException("Unexpected character '{$char}' at line {$this->line}");
    }
}
