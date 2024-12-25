<?php

namespace DesiredPatterns\Pipeline;

/**
 * Pipeline pattern implementation for processing data through a series of operations.
 *
 * @template T
 */
class Pipeline
{
    /**
     * @var T
     */
    private mixed $value;

    /**
     * @param T $value
     */
    private function __construct(mixed $value)
    {
        $this->value = $value;
    }

    /**
     * Create a new pipeline with an initial value.
     *
     * @template U
     * @param U $value
     * @return self<U>
     */
    public static function of(mixed $value): self
    {
        return new self($value);
    }

    /**
     * Apply a transformation function to the current value.
     *
     * @template U
     * @param callable(T): U $fn
     * @return self<U>
     */
    public function pipe(callable $fn): self
    {
        return new self($fn($this->value));
    }

    /**
     * Get the final value from the pipeline.
     *
     * @return T
     */
    public function get(): mixed
    {
        return $this->value;
    }

    /**
     * Map multiple values through the pipeline.
     *
     * @template U
     * @param array<T> $values
     * @param callable(T): U $fn
     * @return array<U>
     */
    public function map(array $values, callable $fn): array
    {
        return array_map($fn, $values);
    }

    /**
     * Chain multiple operations in sequence.
     *
     * @param callable[] $operations
     * @return self
     */
    public function through(array $operations): self
    {
        return array_reduce(
            $operations,
            fn($carry, $operation) => $carry->pipe($operation),
            $this
        );
    }

    /**
     * Apply a side effect without modifying the value.
     *
     * @param callable(T): void $fn
     * @return self<T>
     */
    public function tap(callable $fn): self
    {
        $fn($this->value);
        return $this;
    }

    /**
     * Conditionally apply a transformation.
     *
     * @template U
     * @param callable(T): bool $predicate
     * @param callable(T): U $fn
     * @return self<T|U>
     */
    public function when(callable $predicate, callable $fn): self
    {
        if ($predicate($this->value)) {
            return $this->pipe($fn);
        }
        return $this;
    }

    /**
     * Handle errors in the pipeline.
     *
     * @template U
     * @param callable(T): U $fn
     * @param callable(\Throwable): U $errorHandler
     * @return self<U>
     */
    public function try(callable $fn, callable $errorHandler): self
    {
        try {
            return $this->pipe($fn);
        } catch (\Throwable $e) {
            return new self($errorHandler($e));
        }
    }
}
