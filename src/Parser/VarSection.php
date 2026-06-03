<?php

declare(strict_types = 1);

namespace Phortugol\Parser;

use Phortugol\Enums\TokenType;
use Phortugol\Parser\Nodes\ArrayDeclarationNode;

final readonly class VarSection
{
    public function __construct(
        private Parser $parser,
    ) {
    }

    /**
     * @return list<ArrayDeclarationNode>
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

            $names = [$this->parser->stream->advance()];

            while ($this->parser->stream->match(TokenType::COMMA)) {
                $names[] = $this->parser->stream->consume(type: TokenType::IDENTIFIER, message: 'Expected variable name after ","');
            }

            $this->parser->stream->consume(type: TokenType::COLON, message: 'Expected ":" after variable name');

            if ($this->parser->stream->match(TokenType::VETOR)) {
                $this->parser->stream->consume(type: TokenType::LEFT_BRACKET, message: 'Expected "[" after "vetor"');
                $start = $this->parser->expression();
                $this->parser->stream->consume(type: TokenType::DOTDOT, message: 'Expected ".." in vector range');
                $end = $this->parser->expression();
                $this->parser->stream->consume(type: TokenType::RIGHT_BRACKET, message: 'Expected "]" after vector range');
                $this->parser->stream->consume(type: TokenType::DE, message: 'Expected "de" after vector range');
                $this->parser->stream->advance();

                foreach ($names as $name) {
                    $declarations[] = new ArrayDeclarationNode($name->lexeme, $start, $end);
                }
            } else {
                $this->parser->stream->advance();
            }
        }

        return $declarations;
    }
}
