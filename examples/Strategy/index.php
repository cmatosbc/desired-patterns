<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use DesiredPatterns\Strategy\StrategyContext;
use DesiredPatterns\Examples\Strategy\Sorting\QuickSortStrategy;

// Sample data
$data = [
    ['id' => 3, 'name' => 'John'],
    ['id' => 1, 'name' => 'Jane'],
    ['id' => 2, 'name' => 'Bob'],
];

// Create context with strategy
$context = new StrategyContext();
$context->addStrategy(
    new QuickSortStrategy(),
    ['sort_key' => 'id']
);

// Execute strategy
try {
    $sortedData = $context->executeStrategy($data);
    print_r($sortedData);
} catch (RuntimeException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
