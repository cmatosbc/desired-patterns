<?php

namespace DesiredPatterns\Examples\Pool;

use DesiredPatterns\Pool\PoolFactory;
use PDO;

class DatabaseService
{
    public function __construct(
        private readonly string $dsn,
        private readonly string $username,
        private readonly string $password,
        private readonly int $minConnections = 2,
        private readonly int $maxConnections = 10
    ) {}
    
    private function getPool()
    {
        return PoolFactory::getPool(
            'database',
            PooledDatabaseConnection::class,
            [
                'min_instances' => $this->minConnections,
                'max_instances' => $this->maxConnections,
                'constructor_args' => [
                    $this->dsn,
                    $this->username,
                    $this->password,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                ]
            ]
        );
    }
    
    public function executeQuery(string $sql, array $params = []): array
    {
        /** @var PooledDatabaseConnection $connection */
        $connection = $this->getPool()->acquire();
        
        try {
            $stmt = $connection->getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } finally {
            $this->getPool()->release($connection);
        }
    }
    
    public function getPoolStats(): array
    {
        return $this->getPool()->getStatistics();
    }
}
