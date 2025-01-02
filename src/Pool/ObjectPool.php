<?php

namespace DesiredPatterns\Pool;

use DesiredPatterns\Contracts\PoolableInterface;
use InvalidArgumentException;
use ReflectionClass;
use RuntimeException;
use WeakMap;

class ObjectPool
{
    private readonly WeakMap $inUseObjects;
    private array $availableObjects = [];
    private array $statistics = [
        'created' => 0,
        'reused' => 0,
        'released' => 0,
    ];

    public function __construct(
        private readonly string $className,
        private readonly int $minInstances = 0,
        private readonly int $maxInstances = 10,
        private readonly array $constructorArgs = []
    ) {
        $this->validateClass();
        $this->inUseObjects = new WeakMap();
        $this->initializePool();
    }

    private function validateClass(): void
    {
        $reflection = new ReflectionClass($this->className);
        if (!$reflection->implementsInterface(PoolableInterface::class)) {
            throw new InvalidArgumentException(
                "Class {$this->className} must implement PoolableInterface"
            );
        }
    }

    private function initializePool(): void
    {
        for ($i = 0; $i < $this->minInstances; $i++) {
            $this->availableObjects[] = $this->createNewInstance();
        }
    }

    private function createNewInstance(): object
    {
        $reflection = new ReflectionClass($this->className);
        $instance = $reflection->newInstanceArgs($this->constructorArgs);
        $this->statistics['created']++;
        return $instance;
    }

    public function acquire(): object
    {
        if (empty($this->availableObjects)) {
            if (count($this->inUseObjects) < $this->maxInstances) {
                $object = $this->createNewInstance();
            } else {
                throw new RuntimeException('Pool exhausted: Maximum number of instances reached');
            }
        } else {
            $object = array_pop($this->availableObjects);
            $this->statistics['reused']++;
        }

        $this->inUseObjects[$object] = true;
        return $object;
    }

    public function release(object $object): void
    {
        if (!isset($this->inUseObjects[$object])) {
            throw new InvalidArgumentException('Object does not belong to this pool');
        }

        if ($object instanceof PoolableInterface) {
            $object->reset();
            if ($object->validate()) {
                $this->availableObjects[] = $object;
                $this->statistics['released']++;
            }
        }

        unset($this->inUseObjects[$object]);
    }

    public function getStatistics(): array
    {
        return [
            ...$this->statistics,
            'available' => count($this->availableObjects),
            'in_use' => count($this->inUseObjects),
        ];
    }
}
