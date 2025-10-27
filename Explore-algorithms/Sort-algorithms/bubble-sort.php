<?php

function bubbleSort(&$elements): void
{
    $length = count($elements);
    for ($iteration = 0; $iteration < $length - 1; $iteration++) {
        for ($index = 0; $index < $length - 1 - $iteration; $index++) {
            if ($elements[$index] > $elements[$index + 1]) {
                [$elements[$index], $elements[$index + 1]] = [$elements[$index + 1], $elements[$index]];
                print_r($elements);
            }
        }
    }
}

$elements = [4, 7, 6, 2, 1];
bubbleSort($elements);
echo "RESULT\n";
print_r($elements);
