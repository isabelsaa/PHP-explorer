<?php

declare(strict_types=1);

const FUNCTION_DECLARATION_KIND = ast\AST_FUNC_DECL;
const ECHO_KIND = ast\AST_ECHO;
const FUNCTION_NAME_KIND = ast\AST_NAME;
const FUNCTION_CALL_KIND = ast\AST_CALL;

const PROHIBITED_FUNCTIONS = ['dd', 'var_dump'];

function isFunctionDeclaration(ast\Node $node): bool
{
    return $node->kind === FUNCTION_DECLARATION_KIND;
}

function isProhibitedFunctionCall(mixed $node): ?array
{
    if ($node->kind !== FUNCTION_CALL_KIND) {
        return null;
    }

    $nameNode = $node->children['expr'] ?? null;
    if (
        $nameNode instanceof ast\Node
        && $nameNode->kind === FUNCTION_NAME_KIND
    ) {
        $functionName = strtolower($nameNode->children['name']);

        if (in_array($functionName, PROHIBITED_FUNCTIONS, true)) {
            return [
                'message' => "There is a \"$functionName()\" detected, please remove it",
                'lineno' => $node->lineno,
            ];
        }
    }

    return null;

}

function analyzeAst(mixed $node, bool $isInsideFunctionScope = false): Generator
{
    if ($node instanceof ast\Node) {
        if (isFunctionDeclaration($node)) {
            $isInsideFunctionScope = true;
        }

        if ($node->kind === ECHO_KIND && $isInsideFunctionScope) {
            yield [
                'message' => 'There is an "echo", please remove it.',
                'lineno' => $node->lineno,
            ];
        }

        if ($issue = isProhibitedFunctionCall($node)) {
            yield $issue;
        }

        foreach ($node->children as $child) {
            yield from analyzeAst($child, $isInsideFunctionScope);
        }
    }
}


$codeToAnalyse = <<<'PHP'
<?php

function helloWorld(string $name) {
    echo 'Hello'. $name;
    dd($name);
    var_dump($name);
}

helloWorld('Mary');
PHP;
$version = ast\get_supported_versions()[0];
$ast = ast\parse_code($codeToAnalyse, $version);
if (!$ast) {
    exit("Error parsing the code");
}

foreach (analyzeAst($ast) as $issue) {
    echo "[Line {$issue['lineno']}] {$issue['message']}\n";
}
