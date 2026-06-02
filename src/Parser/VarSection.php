<?php

declare(strict_types = 1);

namespace Phortugol\Parser;

use Phortugol\Enums\TokenType;
use Phortugol\Parser\Nodes\ArrayDeclNode;

final readonly class VarSection
{
    public function __construct(
        private Parser $parser,
    ) {
    }

    /**
     * @return list<ArrayDeclNode>
     */
    public function parse(): array
    {
        if (! $this->parser->stream->check(TokenType::VAR)) {
            return [];
        }

        $this->parser->stream->advance();

        $declarations = [];

        while (! $this->parser->stream->isAtEnd && ! $this->parser->stream->check(TokenType::INICIO)) {
            if (! $this->parser->stream->check(TokenType::IDENTIFIER)) {
                $this->parser->stream->advance();

                continue;
            }

            $name = $this->parser->stream->advance();
            $this->parser->stream->consume(type: TokenType::COLON, message: 'Expected ":" after variable name');

            if ($this->parser->stream->match(TokenType::VETOR)) {
                $this->parser->stream->consume(type: TokenType::LEFT_BRACKET, message: 'Expected "[" after "vetor"');
                $start = $this->parser->expression();
                $this->parser->stream->consume(type: TokenType::DOTDOT, message: 'Expected ".." in vector range');
                $end = $this->parser->expression();
                $this->parser->stream->consume(type: TokenType::RIGHT_BRACKET, message: 'Expected "]" after vector range');
                $this->parser->stream->consume(type: TokenType::DE, message: 'Expected "de" after vector range');
                $this->parser->stream->advance();
                $declarations[] = new ArrayDeclNode($name->lexeme, $start, $end);
            } else {
                $this->parser->stream->advance();
            }
        }

        return $declarations;
    }
}
