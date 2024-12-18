<?php

namespace DesiredPatterns\Specifications\Composite;

use DesiredPatterns\Specifications\AbstractSpecification;
use DesiredPatterns\Contracts\SpecificationContract;

readonly class NotSpecification extends AbstractSpecification
{
    public function __construct(
        private readonly SpecificationContract $specification
    ) {}

    public function isSatisfiedBy(mixed $candidate): bool
    {
        return !$this->specification->isSatisfiedBy($candidate);
    }
}
