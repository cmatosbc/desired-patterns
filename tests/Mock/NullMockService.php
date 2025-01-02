<?php

namespace DesiredPatterns\Tests\Mock;

use DesiredPatterns\NullObject\AbstractNullObject;

class NullMockService extends AbstractNullObject implements MockNullableService
{
    public function performAction(string $action): string
    {
        return '';
    }
    
    public function getLastAction(): ?string
    {
        return null;
    }
    
    public function getActionCount(): int
    {
        return 0;
    }
}
