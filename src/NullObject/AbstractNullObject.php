<?php

namespace DesiredPatterns\NullObject;

abstract class AbstractNullObject implements NullableInterface
{
    public function isNull(): bool
    {
        return true;
    }
}
