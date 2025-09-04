<?php

declare(strict_types=1);

function helloWorld(string $name): void
{
    echo 'Hello'. $name;
    dd($name);
    var_dump($name);
}

helloWorld('Mary');