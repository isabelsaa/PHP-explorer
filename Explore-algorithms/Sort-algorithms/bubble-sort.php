<?php

function bubbleSort(&$elements): void
{
    $length = count($elements);
    for ($iteration = 0; $iteration < $length - 1; $iteration++) {
        for ($index = 0; $index < $length - 1 - $iteration; $index++) {
            if ($elements[$index] > $elements[$index + 1]) {
                [$elements[$index], $elements[$index + 1]] = [$elements[$index + 1], $elements[$index]];
                //print_r($elements);
            }
        }
    }
}

function bubbleSortOptimized(&$elements): void
{
    $length = count($elements);
    for ($iteration = 0; $iteration < $length - 1; $iteration++) {
        $swapped = false;
        for ($index = 0; $index < $length - 1 - $iteration; $index++) {
            if ($elements[$index] > $elements[$index + 1]) {
                [$elements[$index], $elements[$index + 1]] = [$elements[$index + 1], $elements[$index]];
                $swapped = true;
                //print_r($elements);
            }
        }
        if (!$swapped) {
            break;
        }
    }
}

function showArrayState(string $value, array $elements): void
{
    echo $value . ': [' . implode(', ', $elements) . "]\n";
}

$original = [3, 1, 2, 4, 7, 8, 1, 9];

echo "=== Bubble Sort (normal) ===\n";
showArrayState('Desordenado', $original);

$elements = $original;
$start = microtime(true);
bubbleSort($elements);
$end = microtime(true);
$executionTime = $end - $start;

showArrayState('Ordenado', $elements);
printf("Tiempo de ejecución: %.6f segundos\n\n", $executionTime);

echo "=== Bubble Sort (optimizado) ===\n";
showArrayState('Desordenado', $original);

$elements = $original;
$start = microtime(true);
bubbleSortOptimized($elements);
$end = microtime(true);
$executionTime = $end - $start;

showArrayState('Ordenado', $elements);
printf("Tiempo de ejecución: %.6f segundos\n", $executionTime);