<?php

namespace DesiredPatterns\Specifications\Composite;

use DesiredPatterns\Contracts\SpecificationContract;
use DesiredPatterns\Specifications\AbstractSpecification;

class OrSpecification extends AbstractSpecification
{
    public function __construct(
        private SpecificationContract $left,
        private SpecificationContract $right
    ) {}

    public function isSatisfiedBy(mixed $candidate): bool
    {
        return $this->left->isSatisfiedBy($candidate) || $this->right->isSatisfiedBy($candidate);
    }
}
