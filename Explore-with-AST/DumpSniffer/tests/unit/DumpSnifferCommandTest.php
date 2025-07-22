<?php

declare(strict_types=1);

use DumpSniffer\DumpSnifferCommand;

it('shows usage message when no filename is provided', function () {
    $command = new DumpSnifferCommand();
    ob_start();
    $exitCode = $command->run(['./bin/DumpSniffer.php']);
    $output = ob_get_clean();

    expect($exitCode)->toBe(1)
        ->and($output)->toContain('Usage: php DumpSniffer.php <file-or-directory-to-analyze.php>');
});