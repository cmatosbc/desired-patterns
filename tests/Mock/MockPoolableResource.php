<?php

namespace DesiredPatterns\Tests\Mock;

use DesiredPatterns\Contracts\PoolableInterface;

class MockPoolableResource implements PoolableInterface
{
    private bool $isValid = true;
    private int $resetCount = 0;
    private int $validateCount = 0;
    
    public function __construct(
        private readonly string $id
    ) {}
    
    public function getId(): string
    {
        return $this->id;
    }
    
    public function reset(): void
    {
        $this->resetCount++;
    }
    
    public function validate(): bool
    {
        $this->validateCount++;
        return $this->isValid;
    }
    
    public function invalidate(): void
    {
        $this->isValid = false;
    }
    
    public function getResetCount(): int
    {
        return $this->resetCount;
    }
    
    public function getValidateCount(): int
    {
        return $this->validateCount;
    }
}
