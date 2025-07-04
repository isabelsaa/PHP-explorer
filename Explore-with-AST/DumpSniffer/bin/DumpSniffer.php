#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use DumpSniffer\DumpSnifferCommand;

$command = new DumpSnifferCommand();

exit($command->run($argv));