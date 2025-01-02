<?php

namespace DesiredPatterns\Examples\NullObject;

use DesiredPatterns\NullObject\Logger\LoggerInterface;
use DesiredPatterns\NullObject\Logger\LoggerFactory;

class UserService
{
    private readonly LoggerInterface $logger;
    
    public function __construct(?string $logFile = null)
    {
        $this->logger = LoggerFactory::create($logFile);
    }
    
    public function createUser(string $username, string $email): void
    {
        // Simulate user creation
        $this->logger->log('info', 'Creating new user', [
            'username' => $username,
            'email' => $email
        ]);
        
        // Some user creation logic here...
        
        $this->logger->log('info', 'User created successfully', [
            'username' => $username
        ]);
    }
    
    public function deleteUser(string $username): void
    {
        $this->logger->log('warning', 'Deleting user', [
            'username' => $username
        ]);
        
        // Some user deletion logic here...
        
        $this->logger->log('info', 'User deleted successfully', [
            'username' => $username
        ]);
    }
    
    public function getLogs(): array
    {
        return $this->logger->getLogs();
    }
}
