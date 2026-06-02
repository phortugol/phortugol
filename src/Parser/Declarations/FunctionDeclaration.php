<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Declarations;

use Phortugol\Concerns\Parser\ParsesParameters;
use Phortugol\Contracts\Parser\Declaration;
use Phortugol\Enums\TokenType;
use Phortugol\Parser\Nodes\FunctionNode;
use Phortugol\Parser\Parser;

final readonly class FunctionDeclaration implements Declaration
{
    use ParsesParameters;

    public function __invoke(Parser $parser): FunctionNode
    {
        $parser->stream->advance();
        $name = $parser->stream->consume(type: TokenType::IDENTIFIER, message: 'Expected function name');
        $parameters = $this->parameters($parser->stream);
        $parser->stream->consume(type: TokenType::COLON, message: 'Expected ":" before return type');
        $parser->stream->advance();
        $body = $parser->block([TokenType::FIMFUNCAO]);
        $parser->stream->consume(type: TokenType::FIMFUNCAO, message: 'Expected "fimfuncao"');

        return new FunctionNode($name->lexeme, $parameters, $body);
    }
}
