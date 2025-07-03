<?php

declare(strict_types=1);

use DumpSniffer\Analyzer;


dataset('prohibited functions', [
    'dd detection' =>
        [
            'code' => <<<'PHP'
        <?php
        function functionWithDD(string $name): void
        {
            dd($name);
        }
        functionWithDD('Mary');
        PHP,
            'expected' => [
                'message' => 'There is a "dd()" detected, please remove it',
                'lineno' => 4,
            ],
        ],
    'var_dump detection' =>
        [
            'code' => <<<'PHP'
        <?php
        function functionWithVarDump(string $name): void
        {
            if(true)
                var_dump($name);
        }
        functionWithVarDump('Mary');
        PHP,
            'expected' => [
                'message' => 'There is a "var_dump()" detected, please remove it',
                'lineno' => 5,
            ],
        ],
    'echo detection' =>
        [
            'code' => <<<'PHP'
        <?php
        function functionWithEcho(string $name): void
        {
            if(true)
                echo "my name is".$name."\n";
        }
        functionWithEcho('Mary');
        PHP,
            'expected' => [
                'message' => 'There is an "echo", please remove it.',
                'lineno' => 5,
            ],
        ],
    'clean function' => [
        'code' => <<<'PHP'
        <?php
        function cleanedFunction(string $name): string
        {
            if(true);
            return $name;
        }
        cleanedFunction('Mary');
        PHP,
        'expected' => null,
    ],
]);

it('Detects forgotten dumps and echoes', function (string $code, ?array $expected) {
    $version = \ast\get_supported_versions()[0];
    $ast = \ast\parse_code($code, $version);
    $analyzer = new Analyzer();
    $result = iterator_to_array($analyzer->analyzeAst($ast));
    $assertion = $expected === null
        ? fn() => expect($result)->toBeEmpty()
        : fn() => expect($result)->toContain($expected);

    $assertion();
})->with('prohibited functions');