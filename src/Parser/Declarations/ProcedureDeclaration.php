<?php

declare(strict_types = 1);

namespace Phortugol\Parser\Declarations;

use Phortugol\Concerns\Parser\ParsesParameters;
use Phortugol\Contracts\Parser\Declaration;
use Phortugol\Enums\TokenType;
use Phortugol\Parser\Nodes\ProcedureNode;
use Phortugol\Parser\Parser;

final readonly class ProcedureDeclaration implements Declaration
{
    use ParsesParameters;

    public function __invoke(Parser $parser): ProcedureNode
    {
        $parser->stream->advance();

        $name = $parser->stream->consume(type: TokenType::IDENTIFIER, message: 'Expected procedure name');

        $parameters = $this->parameters($parser->stream);

        $body = $parser->block([TokenType::FIMPROCEDIMENTO]);

        $parser->stream->consume(type: TokenType::FIMPROCEDIMENTO, message: 'Expected "fimprocedimento"');

        return new ProcedureNode($name->lexeme, $parameters, $body);
    }
}
