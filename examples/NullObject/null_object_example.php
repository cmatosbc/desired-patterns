<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use DesiredPatterns\Examples\NullObject\UserService;

// Example 1: With logging
$userServiceWithLogs = new UserService(__DIR__ . '/logs/user.log');
$userServiceWithLogs->createUser('john_doe', 'john@example.com');
$userServiceWithLogs->deleteUser('john_doe');

echo "Logs from service with logging:\n";
print_r($userServiceWithLogs->getLogs());

// Example 2: Without logging (using NullLogger)
$userServiceWithoutLogs = new UserService();
$userServiceWithoutLogs->createUser('jane_doe', 'jane@example.com');
$userServiceWithoutLogs->deleteUser('jane_doe');

echo "\nLogs from service without logging:\n";
print_r($userServiceWithoutLogs->getLogs()); // Will be empty array
