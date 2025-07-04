<?php

declare(strict_types=1);

namespace DumpSniffer;
final class DumpSnifferCommand
{
    public function run(array $argv): int
    {
        $filename = $argv[1] ?? null;

        if (!$filename || !file_exists($filename)) {
            $this->printUsage();
            return 1;
        }

        $ast = $this->parseFileToAst($filename);
        if (!$ast) {
            return 1;
        }

        $analyzer = new Analyzer();
        $issues = $analyzer->analyzeAst($ast);
        $this->printIssues($issues);

        return 0;
    }

    private function printUsage(): void
    {
        echo("Usage: php DumpSniffer.php <file-to-analyze.php>\n");
    }


    private function parseFileToAst(string $filename): ?\ast\Node
    {
        $codeToAnalyze = file_get_contents($filename);
        if ($codeToAnalyze === false) {
            fwrite(STDERR, "Error reading the file\n");
            return null;
        }

        $version = \ast\get_supported_versions()[0];
        $ast = \ast\parse_code($codeToAnalyze, $version);

        if (!$ast) {
            fwrite(STDERR, "Error parsing the code\n");
            return null;
        }

        return $ast;
    }

    private function printIssues(\Generator $issues): void
    {
        foreach ($issues as $issue) {
            echo "[Line {$issue['lineno']}] {$issue['message']}\n";
        }
    }
}