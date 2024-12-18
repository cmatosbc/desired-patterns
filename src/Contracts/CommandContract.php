<?php

namespace DesiredPatterns\Contracts;

interface CommandContract
{
    /**
     * Get command name for identification
     */
    public function getName(): string;
    
    /**
     * Get command payload
     * 
     * @return mixed
     */
    public function getPayload(): mixed;
}
