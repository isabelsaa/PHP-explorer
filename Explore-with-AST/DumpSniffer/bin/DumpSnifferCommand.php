<?php

declare(strict_types=1);

require_once '../src/Analyzer.php';

$filename = $argv[1] ?? null;

if (!$filename || !file_exists($filename)) {
    fwrite(STDERR, "Usage: php DumpSniffer.php <file-to-analyze.php>\n");
    exit(1);
}
$codeToAnalyse = file_get_contents($filename);
$version = ast\get_supported_versions()[0];
$ast = ast\parse_code($codeToAnalyse, $version);
if (!$ast) {
    fwrite(STDERR, "Error parsing the code\n");
    exit(1);
}

foreach (analyzeAst($ast) as $issue) {
    echo "[Line {$issue['lineno']}] {$issue['message']}\n";
}