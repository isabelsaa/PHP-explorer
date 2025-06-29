<?php
function helloWorld(string $name): void
{
    echo 'Hello'. $name;
    dd($name);
    var_dump($name);
}

helloWorld('Mary');