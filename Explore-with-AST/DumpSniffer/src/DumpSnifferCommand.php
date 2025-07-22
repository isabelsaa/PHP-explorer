<?php

declare(strict_types=1);

namespace DumpSniffer;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

final class DumpSnifferCommand
{
    public function run(array $arguments): int
    {
        $argumentPath = $arguments[1] ?? null;

        if (!$argumentPath || !file_exists($argumentPath)) {
            $this->printUsage();
            return 1;
        }

        $paths = is_dir($argumentPath)
            ? $this->searchRecursiveAllFiles($argumentPath)
            : [$argumentPath];
        foreach ($paths as $path) {
            echo "Analyzing: {$path}\n";
            $ast = $this->parseFileToAst($path);
            if (!$ast) {
                return 1;
            }
            $analyzer = new Analyzer();
            $issues = $analyzer->analyzeAst($ast);
            $this->printIssues($issues);
        }

        return 0;
    }

    private function searchRecursiveAllFiles(string $directory): array
    {
        $paths = [];

        $directoryIterator = new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($directoryIterator);
        /** @var SplFileInfo $file */
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $paths[] = $file->getRealPath();
            }
        }

        return $paths;
    }

    private function printUsage(): void
    {
        echo("Usage: php DumpSniffer.php <file-or-directory-to-analyze.php>\n");
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