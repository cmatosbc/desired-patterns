<?php

declare(strict_types=1);

namespace DesiredPatterns\Strategy;

use DesiredPatterns\Contracts\StrategyInterface;

/**
 * Abstract class AbstractStrategy
 *
 * Provides a base implementation for strategies with common functionality.
 * Concrete strategies should extend this class and implement the abstract methods.
 */
abstract class AbstractStrategy implements StrategyInterface
{
    /**
     * Configuration options for the strategy
     *
     * @var array<string, mixed>
     */
    protected array $options = [];
    
    /**
     * Configure the strategy with optional parameters
     *
     * @param array $options Configuration options for the strategy
     */
    public function configure(array $options = []): void
    {
        $this->options = array_merge($this->options, $options);
    }
    
    /**
     * Default validation implementation that always returns true
     * Override this method in concrete strategies to implement specific validation logic
     *
     * @param array $data The data to validate
     * @return bool True if the data is valid, false otherwise
     */
    public function validate(array $data): bool
    {
        return true;
    }
    
    /**
     * Determine if this strategy can handle the given data
     *
     * @param array $data The data to check
     * @return bool True if this strategy can handle the data, false otherwise
     */
    abstract public function supports(array $data): bool;

    /**
     * Execute the strategy's algorithm with the given data
     *
     * @param array $data The input data to process
     * @return mixed The result of the strategy execution
     */
    abstract public function execute(array $data): mixed;
}
