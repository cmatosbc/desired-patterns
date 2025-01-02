<?php

namespace DesiredPatterns\NullObject;

interface NullableInterface
{
    /**
     * Check if the object is a null object
     */
    public function isNull(): bool;
}
