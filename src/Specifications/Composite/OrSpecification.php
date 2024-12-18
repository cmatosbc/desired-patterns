<?php

namespace DesiredPatterns\Specifications\Composite;

use DesiredPatterns\Specifications\AbstractSpecification;
use DesiredPatterns\Contracts\SpecificationContract;

readonly class OrSpecification extends AbstractSpecification
{
    public function __construct(
        private readonly SpecificationContract $left,
        private readonly SpecificationContract $right
    ) {}

    public function isSatisfiedBy(mixed $candidate): bool
    {
        return $this->left->isSatisfiedBy($candidate) 
            || $this->right->isSatisfiedBy($candidate);
    }
}
