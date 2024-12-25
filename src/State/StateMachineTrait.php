<?php

namespace DesiredPatterns\State;

use DesiredPatterns\Contracts\StateInterface;
use DesiredPatterns\State\StateException;

/**
 * Trait StateMachineTrait
 *
 * Provides state machine functionality to any class.
 * Makes implementation smoother by handling common state operations.
 */
trait StateMachineTrait
{
    private array $states = [];
    private ?string $currentState = null;
    private array $stateHistory = [];
    private array $context = [];

    /**
     * Register a new state
     *
     * @param StateInterface $state The state to register
     * @param bool $isInitial Whether this is the initial state
     * @return self
     */
    public function addState(StateInterface $state, bool $isInitial = false): self
    {
        $name = $state->getName();
        $this->states[$name] = $state;

        if ($isInitial || $this->currentState === null) {
            $this->currentState = $name;
        }

        return $this;
    }

    /**
     * Transition to a new state if possible
     *
     * @param string $stateName The target state name
     * @param array $context Additional context for the transition
     * @return bool Whether the transition was successful
     * @throws StateException If transition is not allowed
     */
    public function transitionTo(string $stateName, array $context = []): bool
    {
        if (!isset($this->states[$stateName])) {
            throw new StateException("Invalid state: $stateName");
        }

        $currentState = $this->states[$this->currentState];

        if (!$currentState->canTransitionTo($stateName, $this->context)) {
            throw new StateException(
                "Cannot transition from {$this->currentState} to $stateName"
            );
        }

        // Update context
        $this->context = array_merge($this->context, $context);

        // Validate new context against target state rules
        $targetState = $this->states[$stateName];
        if (!$this->validateContext($targetState->getValidationRules())) {
            throw new StateException("Invalid context for state $stateName");
        }

        // Record state change in history
        $this->stateHistory[] = [
            'from' => $this->currentState,
            'to' => $stateName,
            'timestamp' => new \DateTime(),
            'context' => $this->context
        ];

        // Update current state
        $this->currentState = $stateName;

        return true;
    }

    /**
     * Get the current state object
     *
     * @return StateInterface
     */
    public function getCurrentState(): StateInterface
    {
        return $this->states[$this->currentState];
    }

    /**
     * Get available transitions from current state
     *
     * @return array<string>
     */
    public function getAvailableTransitions(): array
    {
        return $this->getCurrentState()->getAllowedTransitions();
    }

    /**
     * Get the complete state history
     *
     * @return array
     */
    public function getStateHistory(): array
    {
        return $this->stateHistory;
    }

    /**
     * Update the context
     *
     * @param array $context New context data
     * @return self
     */
    public function updateContext(array $context): self
    {
        $this->context = array_merge($this->context, $context);

        // Validate against current state's rules
        if ($this->currentState !== null) {
            $currentState = $this->getCurrentState();
            if (!$this->validateContext($currentState->getValidationRules())) {
                throw new StateException('Invalid context for current state');
            }
        }

        return $this;
    }

    /**
     * Get current context
     *
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Validate context against rules
     *
     * @param array $rules Validation rules
     * @return bool
     */
    private function validateContext(array $rules): bool
    {
        foreach ($rules as $key => $rule) {
            // Skip validation if no rule is set
            if (empty($rule)) {
                continue;
            }

            // Check if field exists when required
            if ($rule === 'required') {
                if (!isset($this->context[$key]) || empty($this->context[$key])) {
                    return false;
                }
                continue;
            }

            // Skip other validations if field is not set and not required
            if (!isset($this->context[$key])) {
                continue;
            }

            $value = $this->context[$key];

            // Type validation
            if (str_starts_with($rule, 'type:')) {
                $expectedType = substr($rule, 5);
                $actualType = gettype($value);

                // Special handling for 'double' type
                if ($expectedType === 'double' && is_numeric($value)) {
                    continue;
                }

                if ($actualType !== $expectedType) {
                    return false;
                }
            }
        }

        return true;
    }
}
