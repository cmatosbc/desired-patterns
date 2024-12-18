<?php

namespace DesiredPatterns\Specifications\Composite;

use DesiredPatterns\Specifications\AbstractSpecification;
use DesiredPatterns\Contracts\SpecificationContract;

readonly class AndSpecification extends AbstractSpecification
{
    public function __construct(
        private SpecificationContract $left,
        private SpecificationContract $right
    ) {}

    public function isSatisfiedBy(mixed $candidate): bool
    {
        return $this->left->isSatisfiedBy($candidate) 
            && $this->right->isSatisfiedBy($candidate);
    }
}
