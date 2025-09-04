<?php

declare(strict_types=1);

namespace DumpSniffer\Entity;

final readonly class Issue
{
    public function __construct(
        public int    $lineno,
        public string $message
    )
    {
    }
}