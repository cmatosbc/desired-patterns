<?php

namespace DesiredPatterns\Pipeline;

/**
 * Builder for creating complex pipelines.
 *
 * @template T
 */
class PipelineBuilder
{
    /** @var array<callable> */
    private array $operations = [];

    /** @var array<callable> */
    private array $errorHandlers = [];

    /** @var array<array{callable, string}> */
    private array $validators = [];

    /**
     * Add an operation to the pipeline.
     *
     * @template U
     * @param callable(T): U $operation
     * @return self<U>
     */
    public function add(callable $operation): self
    {
        $this->operations[] = $operation;
        return $this;
    }

    /**
     * Build and execute the pipeline with an initial value.
     *
     * @template U
     * @param U $initialValue
     * @return Pipeline<U>
     */
    public function build(mixed $initialValue): Pipeline
    {
        $pipeline = Pipeline::of($initialValue);

        // Wrap all operations in error handling
        $handleError = function (\Throwable $e) {
            foreach ($this->errorHandlers as $handler) {
                try {
                    return $handler($e);
                } catch (\Throwable) {
                    continue;
                }
            }
            throw $e;
        };

        // Apply operations first
        foreach ($this->operations as $operation) {
            $pipeline = $pipeline->try($operation, $handleError);
        }

        // Then apply validators
        foreach ($this->validators as [$validator, $message]) {
            $pipeline = $pipeline->try(
                function ($value) use ($validator, $message) {
                    if (!$validator($value)) {
                        throw new \InvalidArgumentException($message);
                    }
                    return $value;
                },
                $handleError
            );
        }

        return $pipeline;
    }

    /**
     * Add error handling to the pipeline.
     *
     * @param callable(\Throwable): mixed $errorHandler
     * @return self
     */
    public function withErrorHandling(callable $errorHandler): self
    {
        $this->errorHandlers[] = $errorHandler;
        return $this;
    }

    /**
     * Add validation to the pipeline.
     *
     * @param callable(T): bool $validator
     * @param string $message
     * @return self
     */
    public function withValidation(callable $validator, string $message): self
    {
        $this->validators[] = [$validator, $message];
        return $this;
    }
}
