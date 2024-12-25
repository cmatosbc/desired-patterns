<?php

namespace DesiredPatterns\State;

use DesiredPatterns\Contracts\StateInterface;

/**
 * Abstract base state class with common functionality
 */
abstract class AbstractState implements StateInterface
{
    protected array $allowedTransitions = [];
    protected array $validationRules = [];

    public function getAllowedTransitions(): array
    {
        return $this->allowedTransitions;
    }

    public function canTransitionTo(string $stateName, array $context): bool
    {
        return in_array($stateName, $this->allowedTransitions);
    }

    public function getValidationRules(): array
    {
        return $this->validationRules;
    }

    abstract public function getName(): string;
    abstract public function handle(array $context): array;
}
