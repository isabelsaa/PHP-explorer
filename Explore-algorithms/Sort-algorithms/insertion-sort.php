<?php

function insertionSort(&$elements): void
{
    $length = count($elements);
    for ($currentIndex = 1; $currentIndex < $length; $currentIndex++) {
        $currentValue = $elements[$currentIndex];
        $indexToCompare = $currentIndex - 1;
        while ($indexToCompare >= 0 && $elements[$indexToCompare] > $currentValue) {
            $elements[$indexToCompare + 1] = $elements[$indexToCompare];
            $indexToCompare--;
        }
        $elements[$indexToCompare + 1] = $currentValue;
    }
}

$original = [4, 3, 1, 2];

echo "=== insertion Sort ===\n";
$elements = $original;
$start = microtime(true);
insertionSort($elements);
$end = microtime(true);
$executionTime = $end - $start;
printf("Tiempo de ejecución: %.6f segundos\n", $executionTime);
print_r($elements);
