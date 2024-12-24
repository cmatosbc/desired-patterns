<?php

declare(strict_types=1);

namespace DesiredPatterns\Contracts;

/**
 * Interface StrategyInterface
 *
 * Defines a family of algorithms that can be used interchangeably.
 * Each concrete strategy implements a specific algorithm while maintaining
 * the same interface, allowing them to be swapped at runtime.
 */
interface StrategyInterface
{
    /**
     * Execute the strategy's algorithm with the given data
     *
     * @param array $data The input data to process
     * @return mixed The result of the strategy execution
     */
    public function execute(array $data): mixed;

    /**
     * Determine if this strategy can handle the given data
     *
     * @param array $data The data to check
     * @return bool True if this strategy can handle the data, false otherwise
     */
    public function supports(array $data): bool;

    /**
     * Configure the strategy with optional parameters
     *
     * @param array $options Configuration options for the strategy
     */
    public function configure(array $options = []): void;

    /**
     * Validate the input data before execution
     *
     * @param array $data The data to validate
     * @return bool True if the data is valid, false otherwise
     */
    public function validate(array $data): bool;
}
