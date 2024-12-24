<?php

declare(strict_types=1);

namespace DesiredPatterns\Traits;

/**
 * Trait ConfigurableStrategyTrait
 *
 * Provides configuration management functionality for strategies.
 * This trait allows strategies to be configured with options and
 * validates that required options are provided.
 */
trait ConfigurableStrategyTrait
{
    /**
     * Configuration options for the strategy
     *
     * @var array<string, mixed>
     */
    protected array $options = [];

    /**
     * List of required option keys
     *
     * @var string[]
     */
    protected array $requiredOptions = [];
    
    /**
     * Configure the strategy with options
     *
     * @param array $options Configuration options
     * @throws \InvalidArgumentException When required options are missing
     */
    public function configure(array $options = []): void
    {
        $this->validateOptions($options);
        $this->options = array_merge($this->options, $options);
    }
    
    /**
     * Validate that all required options are present
     *
     * @param array $options Options to validate
     * @throws \InvalidArgumentException When required options are missing
     */
    protected function validateOptions(array $options): void
    {
        $missing = array_diff($this->requiredOptions, array_keys($options));
        if (!empty($missing)) {
            throw new \InvalidArgumentException(
                sprintf('Missing required options: %s', implode(', ', $missing))
            );
        }
    }
    
    /**
     * Get an option value with a default fallback
     *
     * @param string $key The option key
     * @param mixed $default Default value if the option is not set
     * @return mixed The option value or default
     */
    protected function getOption(string $key, mixed $default = null): mixed
    {
        return $this->options[$key] ?? $default;
    }
}
