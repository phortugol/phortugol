<?php

declare(strict_types = 1);

namespace Phortugol\Lexer;

enum TokenType: string
{
    // Literals
    case INTEGER_LITERAL = 'INTEGER_LITERAL';
    case REAL_LITERAL = 'REAL_LITERAL';
    case STRING_LITERAL = 'STRING_LITERAL';

    // Boolean literals (keywords that carry a typed value)
    case FALSO = 'FALSO';
    case VERDADEIRO = 'VERDADEIRO';

    // Type keywords
    case CARACTERE = 'CARACTERE';
    case INTEIRO = 'INTEIRO';
    case LOGICO = 'LOGICO';
    case REAL = 'REAL';

    // Program structure
    case ALGORITMO = 'ALGORITMO';
    case FIMALGORITMO = 'FIMALGORITMO';
    case INICIO = 'INICIO';
    case VAR = 'VAR';

    // Conditionals
    case ENTAO = 'ENTAO';
    case FIMSE = 'FIMSE';
    case SE = 'SE';
    case SENAO = 'SENAO';

    // For loop
    case ATE = 'ATE';
    case DE = 'DE';
    case FIMPARA = 'FIMPARA';
    case PARA = 'PARA';
    case PASSO = 'PASSO';

    // While loop
    case ENQUANTO = 'ENQUANTO';
    case FACA = 'FACA';
    case FIMENQUANTO = 'FIMENQUANTO';

    // Repeat-until loop
    case REPITA = 'REPITA';

    // I/O
    case ESCREVA = 'ESCREVA';
    case ESCREVAL = 'ESCREVAL';
    case LEIA = 'LEIA';

    // Logical operators (keyword form)
    case E = 'E';
    case NAO = 'NAO';
    case OU = 'OU';

    // Arithmetic keyword operators
    case DIV = 'DIV';
    case MOD = 'MOD';

    // Functions and procedures
    case FIMFUNCAO = 'FIMFUNCAO';
    case FIMPROCEDIMENTO = 'FIMPROCEDIMENTO';
    case FUNCAO = 'FUNCAO';
    case INTERROMPA = 'INTERROMPA';
    case PROCEDIMENTO = 'PROCEDIMENTO';
    case RETORNE = 'RETORNE';

    // Switch / case
    case CASO = 'CASO';
    case FIMCASO = 'FIMCASO';
    case OUTROCASO = 'OUTROCASO';
    case SEJA = 'SEJA';

    // Arrays
    case VETOR = 'VETOR';

    // Operators
    case ASSIGN = 'ASSIGN';         // <- or :=
    case EQUAL = 'EQUAL';          // =
    case GREATER = 'GREATER';        // >
    case GREATER_EQUAL = 'GREATER_EQUAL';  // >=
    case LESS = 'LESS';           // <
    case LESS_EQUAL = 'LESS_EQUAL';     // <=
    case MINUS = 'MINUS';          // -
    case NOT_EQUAL = 'NOT_EQUAL';      // <>
    case PLUS = 'PLUS';           // +
    case SLASH = 'SLASH';          // /
    case STAR = 'STAR';           // *

    // Symbols
    case COLON = 'COLON';          // :
    case COMMA = 'COMMA';          // ,
    case DOT = 'DOT';            // .
    case DOTDOT = 'DOTDOT';         // ..
    case LEFT_BRACKET = 'LEFT_BRACKET';   // [
    case LEFT_PAREN = 'LEFT_PAREN';     // (
    case RIGHT_BRACKET = 'RIGHT_BRACKET';  // ]
    case RIGHT_PAREN = 'RIGHT_PAREN';    // )

    // Special
    case EOF = 'EOF';
    case IDENTIFIER = 'IDENTIFIER';
}
