<?php

namespace DesiredPatterns\Tests\Mock;

use DesiredPatterns\NullObject\NullableInterface;

interface MockNullableService extends NullableInterface
{
    public function performAction(string $action): string;
    
    public function getLastAction(): ?string;
    
    public function getActionCount(): int;
}
