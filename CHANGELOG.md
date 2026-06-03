# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.1.1] - 2026-06-02

### Fixed

- `senao se` (else-if) chains now correctly close with a single `fimse`
- `%` is now recognized as the modulo operator (alias for `mod`)
- Multiple variable declarations on the same line (`n, i: inteiro`) are now parsed correctly

## [0.1.0] - 2026-06-02

### Added

- Core interpreter pipeline: Tokenizer → Parser → Runner
- `LexerException`, `ParseException`, `RuntimeException` typed exception hierarchy
- `Runtime` contract (Strategy Pattern) with `TerminalRuntime` and `FakeRuntime` implementations
- `ExecutorDispatcher` — maps each AST node type to a dedicated `NodeExecutor`, keeping `Runner` free of `match`/`instanceof`
- `Environment` for variable scope management during execution
- Data types: `inteiro`, `real`, `caractere`, `logico`
- Assignment: `<-` and `:=` syntax
- I/O: `escreva`, `escreval`, `leia`
- Conditionals: `se/entao/senao/fimse`
- Loops: `enquanto/faca/fimenquanto`, `para/ate/passo/fimpara`, `repita/ate`
- Loop control: `interrompa`
- Loop safety guard: throws `RuntimeException` after 100,000 iterations
- Switch/case: `seja/caso/outrocaso/fimcaso`
- Functions: `funcao/fimfuncao` with typed parameters and `retorne`
- Procedures: `procedimento/fimprocedimento` with typed parameters
- Arrays (vectors): `vetor[n]: tipo` declaration, index read and write
- Operators: arithmetic (`+`, `-`, `*`, `/`, `div`, `mod`), comparison (`=`, `<>`, `<`, `>`, `<=`, `>=`), logical (`e`, `ou`, `nao`)
- CLI runner via `symfony/console`
- `Nodes` fluent factory for building AST nodes with a native Portugol API
- `Scanner` class extracting keyword-to-token mapping from `Tokenizer`
- `HasCoercion` and `BinaryOperations` traits decomposing interpreter logic
- Comprehensive test suite: 62 test cases across lexer, parser, and interpreter feature tests
