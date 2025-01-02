<?php

namespace DesiredPatterns\Tests\Mock;

class MockService implements MockNullableService
{
    private array $actions = [];
    
    public function performAction(string $action): string
    {
        $this->actions[] = $action;
        return "Performed: {$action}";
    }
    
    public function getLastAction(): ?string
    {
        return empty($this->actions) ? null : end($this->actions);
    }
    
    public function getActionCount(): int
    {
        return count($this->actions);
    }
    
    public function isNull(): bool
    {
        return false;
    }
}
