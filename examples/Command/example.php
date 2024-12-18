<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Examples\Command\SimpleCommandBus;
use Examples\Command\User\CreateUserCommand;
use Examples\Command\User\CreateUserHandler;

// Create the command bus
$commandBus = new SimpleCommandBus();

// Register the handler
$commandBus->register(
    CreateUserCommand::class,
    new CreateUserHandler()
);

// Create and dispatch a command
$user = $commandBus->dispatch(new CreateUserCommand(
    email: 'john@example.com',
    password: 'secret123',
    name: 'John Doe'
));

// Verify the result
assert($user->email === 'john@example.com');
assert($user->name === 'John Doe');
assert(password_verify('secret123', $user->password));

echo "User created successfully!\n";
echo "Email: {$user->email}\n";
echo "Name: {$user->name}\n";
