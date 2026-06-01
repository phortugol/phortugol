<?php

declare(strict_types = 1);

namespace Phortugol\Lexer;

use Phortugol\Exceptions\LexerException;

final class Tokenizer
{
    private int $start = 0;

    private int $current = 0;

    private int $line = 1;

    /**
     * @var list<Token>
     */
    private array $tokens = [];

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

    /**
     * @return list<Token>
     */
    public function tokenize(): array
    {
        while (! $this->isAtEnd()) {
            $this->start = $this->current;
            $this->scanToken();
        }

        $this->tokens[] = new Token(TokenType::EOF, '', $this->line);

        return $this->tokens;
    }

    private function scanToken(): void
    {
        $char = $this->advance();

        switch ($char) {
            case '(':
                $this->addToken(TokenType::LEFT_PAREN);
                break;
            case ')':
                $this->addToken(TokenType::RIGHT_PAREN);
                break;
            case '[':
                $this->addToken(TokenType::LEFT_BRACKET);
                break;
            case ']':
                $this->addToken(TokenType::RIGHT_BRACKET);
                break;
            case ',':
                $this->addToken(TokenType::COMMA);
                break;
            case '+':
                $this->addToken(TokenType::PLUS);
                break;
            case '-':
                $this->addToken(TokenType::MINUS);
                break;
            case '*':
                $this->addToken(TokenType::STAR);
                break;
            case '=':
                $this->addToken(TokenType::EQUAL);
                break;
            case '/':
                if ($this->peek() === '/') {
                    $this->skipLineComment();
                } else {
                    $this->addToken(TokenType::SLASH);
                }
                break;
            case '<':
                if ($this->peek() === '-') {
                    $this->current++;
                    $this->addToken(TokenType::ASSIGN);
                } elseif ($this->peek() === '=') {
                    $this->current++;
                    $this->addToken(TokenType::LESS_EQUAL);
                } elseif ($this->peek() === '>') {
                    $this->current++;
                    $this->addToken(TokenType::NOT_EQUAL);
                } else {
                    $this->addToken(TokenType::LESS);
                }
                break;
            case '>':
                if ($this->peek() === '=') {
                    $this->current++;
                    $this->addToken(TokenType::GREATER_EQUAL);
                } else {
                    $this->addToken(TokenType::GREATER);
                }
                break;
            case ':':
                if ($this->peek() === '=') {
                    $this->current++;
                    $this->addToken(TokenType::ASSIGN);
                } else {
                    $this->addToken(TokenType::COLON);
                }
                break;
            case '.':
                if ($this->peek() === '.') {
                    $this->current++;
                    $this->addToken(TokenType::DOTDOT);
                } else {
                    $this->addToken(TokenType::DOT);
                }
                break;
            case '{':
                $this->skipBlockComment();
                break;
            case '"':
                $this->scanString();
                break;
            case ' ':
            case "\t":
            case "\r":
                break;
            case "\n":
                $this->line++;
                break;
            default:
                if (ctype_digit($char)) {
                    $this->scanNumber();
                } elseif (ctype_alpha($char) || $char === '_') {
                    $this->scanIdentifier();
                } else {
                    throw new LexerException("Unexpected character '{$char}' at line {$this->line}");
                }
        }
    }

    private function advance(): string
    {
        return $this->source[$this->current++];
    }

    private function peek(): string
    {
        return $this->isAtEnd() ? "\0" : $this->source[$this->current];
    }

    private function peekNext(): string
    {
        $next = $this->current + 1;

        return $next >= strlen($this->source) ? "\0" : $this->source[$next];
    }

    private function isAtEnd(): bool
    {
        return $this->current >= strlen($this->source);
    }

    private function addToken(TokenType $type, string | int | float | bool | null $value = null): void
    {
        $lexeme = substr($this->source, $this->start, $this->current - $this->start);
        $this->tokens[] = new Token($type, $lexeme, $this->line, $value);
    }

    private function skipLineComment(): void
    {
        while ($this->peek() !== "\n" && ! $this->isAtEnd()) {
            $this->current++;
        }
    }

    private function skipBlockComment(): void
    {
        while ($this->peek() !== '}' && ! $this->isAtEnd()) {
            if ($this->peek() === "\n") {
                $this->line++;
            }
            $this->current++;
        }

        if ($this->isAtEnd()) {
            throw new LexerException("Unterminated block comment at line {$this->line}");
        }

        $this->current++;
    }

    private function scanString(): void
    {
        while ($this->peek() !== '"' && ! $this->isAtEnd()) {
            if ($this->peek() === "\n") {
                $this->line++;
            }
            $this->current++;
        }

        if ($this->isAtEnd()) {
            throw new LexerException("Unterminated string at line {$this->line}");
        }

        $this->current++;

        $value = substr($this->source, $this->start + 1, $this->current - $this->start - 2);
        $this->addToken(TokenType::STRING_LITERAL, $value);
    }

    private function scanNumber(): void
    {
        while (ctype_digit($this->peek())) {
            $this->current++;
        }

        if ($this->peek() === '.' && ctype_digit($this->peekNext())) {
            $this->current++;

            while (ctype_digit($this->peek())) {
                $this->current++;
            }
            $lexeme = substr($this->source, $this->start, $this->current - $this->start);
            $this->addToken(TokenType::REAL_LITERAL, (float) $lexeme);

            return;
        }

        $lexeme = substr($this->source, $this->start, $this->current - $this->start);
        $this->addToken(TokenType::INTEGER_LITERAL, (int) $lexeme);
    }

    private function scanIdentifier(): void
    {
        while (ctype_alnum($this->peek()) || $this->peek() === '_') {
            $this->current++;
        }

        $lexeme = substr($this->source, $this->start, $this->current - $this->start);
        $type = self::KEYWORDS[strtolower($lexeme)] ?? TokenType::IDENTIFIER;

        $value = match ($type) {
            TokenType::VERDADEIRO => true,
            TokenType::FALSO      => false,
            TokenType::IDENTIFIER => $lexeme,
            default               => null,
        };

        $this->addToken($type, $value);
    }
}
