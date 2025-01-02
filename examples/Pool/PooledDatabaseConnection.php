<?php

namespace DesiredPatterns\Examples\Pool;

use DesiredPatterns\Contracts\PoolableInterface;
use PDO;
use PDOException;

class PooledDatabaseConnection implements PoolableInterface
{
    private ?PDO $connection = null;
    
    public function __construct(
        private readonly string $dsn,
        private readonly string $username,
        private readonly string $password,
        private readonly array $options = []
    ) {}
    
    public function getConnection(): PDO
    {
        if ($this->connection === null) {
            $this->connection = new PDO(
                $this->dsn,
                $this->username,
                $this->password,
                $this->options
            );
        }
        return $this->connection;
    }
    
    public function reset(): void
    {
        if ($this->connection !== null) {
            $this->connection->setAttribute(PDO::ATTR_AUTOCOMMIT, true);
            // Reset any connection-specific state
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }
    
    public function validate(): bool
    {
        if ($this->connection === null) {
            return true;
        }
        
        try {
            $this->connection->query('SELECT 1');
            return true;
        } catch (PDOException) {
            $this->connection = null;
            return false;
        }
    }
}
