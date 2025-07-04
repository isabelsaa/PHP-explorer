<?php

declare(strict_types=1);

namespace DumpSniffer;

final class Analyzer
{
    private const FUNCTION_DECLARATION_KIND = \ast\AST_FUNC_DECL;
    private const ECHO_KIND = \ast\AST_ECHO;
    private const FUNCTION_NAME_KIND = \ast\AST_NAME;
    private const FUNCTION_CALL_KIND = \ast\AST_CALL;

    private const array PROHIBITED_FUNCTIONS = ['dd', 'var_dump'];

    public function isFunctionDeclaration(\ast\Node $node): bool
    {
        return $node->kind === self::FUNCTION_DECLARATION_KIND;
    }

    public function isProhibitedFunctionCall(mixed $node): ?array
    {
        if ($node->kind !== self::FUNCTION_CALL_KIND) {
            return null;
        }

        $nameNode = $node->children['expr'] ?? null;
        if (
            $nameNode instanceof \ast\Node
            && $nameNode->kind === self::FUNCTION_NAME_KIND
        ) {
            $functionName = strtolower($nameNode->children['name']);

            if (in_array($functionName, self::PROHIBITED_FUNCTIONS, true)) {
                return [
                    'message' => "There is a \"$functionName()\" detected, please remove it",
                    'lineno' => $node->lineno,
                ];
            }
        }

        return null;

    }

    public function analyzeAst(mixed $node, bool $isInsideFunctionScope = false): \Generator
    {
        if ($node instanceof \ast\Node) {
            if ($this->isFunctionDeclaration($node)) {
                $isInsideFunctionScope = true;
            }

            if ($node->kind === self::ECHO_KIND && $isInsideFunctionScope) {
                yield [
                    'message' => 'There is an "echo", please remove it.',
                    'lineno' => $node->lineno,
                ];
            }

            if ($issue = $this->isProhibitedFunctionCall($node)) {
                yield $issue;
            }

            foreach ($node->children as $child) {
                yield from $this->analyzeAst($child, $isInsideFunctionScope);
            }
        }
    }
}
