<?php

declare(strict_types=1);

namespace DesiredPatterns\Strategy;

use DesiredPatterns\Contracts\StrategyInterface;
use RuntimeException;

/**
 * Class StrategyContext
 *
 * Manages a collection of strategies and handles the selection and execution
 * of the appropriate strategy based on the input data. This class implements
 * the Strategy pattern by providing a way to define a family of algorithms,
 * encapsulate each one, and make them interchangeable.
 */
class StrategyContext
{
    /**
     * Collection of available strategies
     *
     * @var StrategyInterface[]
     */
    private array $strategies = [];

    /**
     * Default strategy to use when no other strategy matches
     */
    private ?StrategyInterface $defaultStrategy = null;
    
    /**
     * Add a strategy with optional configuration
     *
     * @param StrategyInterface $strategy The strategy to add
     * @param array $config Optional configuration for the strategy
     * @return self For method chaining
     */
    public function addStrategy(StrategyInterface $strategy, array $config = []): self
    {
        $strategy->configure($config);
        $this->strategies[] = $strategy;
        return $this;
    }
    
    /**
     * Set a default strategy to use when no other strategy matches
     *
     * @param StrategyInterface $strategy The default strategy
     * @return self For method chaining
     */
    public function setDefaultStrategy(StrategyInterface $strategy): self
    {
        $this->defaultStrategy = $strategy;
        return $this;
    }
    
    /**
     * Execute the appropriate strategy for the given data
     *
     * @param array $data The input data to process
     * @return mixed The result of the strategy execution
     * @throws RuntimeException When no suitable strategy is found or validation fails
     */
    public function executeStrategy(array $data): mixed
    {
        $strategy = $this->resolveStrategy($data);
        
        if (!$strategy->validate($data)) {
            throw new RuntimeException('Invalid data for strategy execution');
        }
        
        return $strategy->execute($data);
    }
    
    /**
     * Find the first strategy that supports the given data
     *
     * @param array $data The input data to check
     * @return StrategyInterface The resolved strategy
     * @throws RuntimeException When no suitable strategy is found
     */
    private function resolveStrategy(array $data): StrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($data)) {
                return $strategy;
            }
        }
        
        if ($this->defaultStrategy !== null) {
            return $this->defaultStrategy;
        }
        
        throw new RuntimeException('No suitable strategy found for the given data');
    }
}
