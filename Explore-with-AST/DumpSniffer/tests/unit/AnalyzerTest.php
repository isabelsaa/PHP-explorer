<?php

declare(strict_types=1);

use DumpSniffer\Analyzer;
use DumpSniffer\Entity\Issue;

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
            'expected' => [new Issue(4, 'There is a "dd()" , please remove it.')]

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
            'expected' => [new Issue(5, 'There is a "var_dump()" , please remove it.')]
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
            'expected' => [new Issue(5, 'There is an "echo", please remove it.')]
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

    ],]);

it('Detects forgotten dumps and echoes', function (string $code, ?array $expected) {
    $version = \ast\get_supported_versions()[0];
    $ast = \ast\parse_code($code, $version);
    $analyzer = new Analyzer();
    $result = iterator_to_array($analyzer->analyzeAst($ast));

    $assertion = $expected === null
        ? fn() => expect($result)->toBeEmpty()
        : fn() => expect($result)->toEqual($expected);

    $assertion();
})->with('prohibited functions');