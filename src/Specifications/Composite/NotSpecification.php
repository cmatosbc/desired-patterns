<?php

namespace DesiredPatterns\Specifications\Composite;

use DesiredPatterns\Contracts\SpecificationContract;
use DesiredPatterns\Specifications\AbstractSpecification;

class NotSpecification extends AbstractSpecification
{
    public function __construct(
        private SpecificationContract $specification
    ) {}

    public function isSatisfiedBy(mixed $candidate): bool
    {
        return !$this->specification->isSatisfiedBy($candidate);
    }
}
