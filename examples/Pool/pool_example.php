<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use DesiredPatterns\Examples\Pool\DatabaseService;

// Create a database service with connection pooling
$dbService = new DatabaseService(
    dsn: 'mysql:host=localhost;dbname=test',
    username: 'root',
    password: 'password',
    minConnections: 2,
    maxConnections: 5
);

// Execute some queries
try {
    // First query
    $results1 = $dbService->executeQuery('SELECT * FROM users WHERE id = ?', [1]);
    echo "Query 1 results: " . json_encode($results1) . "\n";
    
    // Second query
    $results2 = $dbService->executeQuery('SELECT * FROM users WHERE active = ?', [true]);
    echo "Query 2 results: " . json_encode($results2) . "\n";
    
    // Get pool statistics
    $stats = $dbService->getPoolStats();
    echo "Pool statistics: " . json_encode($stats, JSON_PRETTY_PRINT) . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
