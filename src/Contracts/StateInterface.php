<?php

namespace DesiredPatterns\Contracts;

/**
 * Interface StateInterface
 *
 * Defines the contract for all states in the system.
 * Each state implements how it handles various actions
 * and what the next state should be.
 */
interface StateInterface
{
    /**
     * Get the unique name of this state
     *
     * @return string The state name
     */
    public function getName(): string;

    /**
     * Handle the current state action
     *
     * @param array $context Current state data
     * @return array Result of the state action
     */
    public function handle(array $context): array;

    /**
     * Determine if this state can transition to the specified state
     *
     * @param string $stateName Name of the target state
     * @param array $context Current state data
     * @return bool True if transition is allowed
     */
    public function canTransitionTo(string $stateName, array $context): bool;

    /**
     * Get all states that this state can transition to
     *
     * @return array<string> List of allowed state names
     */
    public function getAllowedTransitions(): array;

    /**
     * Get validation rules for this state
     *
     * @return array<string, string> Validation rules
     */
    public function getValidationRules(): array;
}
