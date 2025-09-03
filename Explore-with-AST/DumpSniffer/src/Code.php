<?php

declare(strict_types=1);

function helloWorld(string $name): void
{
    echo 'Hello'. $name;
}

helloWorld('Mary');