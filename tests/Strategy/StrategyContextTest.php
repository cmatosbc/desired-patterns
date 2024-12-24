<?php

declare(strict_types=1);

namespace Tests\Strategy;

use DesiredPatterns\Strategy\StrategyContext;
use DesiredPatterns\Strategy\AbstractStrategy;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class MockStrategy extends AbstractStrategy
{
    private bool $shouldSupport;
    private mixed $returnValue;
    private bool $shouldValidate;

    public function __construct(bool $shouldSupport = true, mixed $returnValue = null, bool $shouldValidate = true)
    {
        $this->shouldSupport = $shouldSupport;
        $this->returnValue = $returnValue;
        $this->shouldValidate = $shouldValidate;
    }

    public function supports(array $data): bool
    {
        return $this->shouldSupport;
    }

    public function execute(array $data): mixed
    {
        return $this->returnValue ?? $data;
    }

    public function validate(array $data): bool
    {
        return $this->shouldValidate;
    }
}

class StrategyContextTest extends TestCase
{
    private StrategyContext $context;

    protected function setUp(): void
    {
        $this->context = new StrategyContext();
    }

    public function testAddStrategyReturnsFluently(): void
    {
        $strategy = new MockStrategy();
        $result = $this->context->addStrategy($strategy);

        $this->assertSame($this->context, $result);
    }

    public function testSetDefaultStrategyReturnsFluently(): void
    {
        $strategy = new MockStrategy();
        $result = $this->context->setDefaultStrategy($strategy);

        $this->assertSame($this->context, $result);
    }

    public function testExecuteStrategyWithMatchingStrategy(): void
    {
        $expectedData = ['test' => 'data'];
        $strategy = new MockStrategy(true, $expectedData);
        
        $this->context->addStrategy($strategy);
        $result = $this->context->executeStrategy(['input' => 'data']);

        $this->assertSame($expectedData, $result);
    }

    public function testExecuteStrategyWithDefaultStrategy(): void
    {
        $expectedData = ['default' => 'data'];
        $nonMatchingStrategy = new MockStrategy(false);
        $defaultStrategy = new MockStrategy(true, $expectedData);

        $this->context->addStrategy($nonMatchingStrategy)
                     ->setDefaultStrategy($defaultStrategy);

        $result = $this->context->executeStrategy(['input' => 'data']);

        $this->assertSame($expectedData, $result);
    }

    public function testExecuteStrategyThrowsExceptionWhenNoStrategyFound(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No suitable strategy found for the given data');

        $nonMatchingStrategy = new MockStrategy(false);
        $this->context->addStrategy($nonMatchingStrategy);
        
        $this->context->executeStrategy(['input' => 'data']);
    }

    public function testExecuteStrategyThrowsExceptionWhenValidationFails(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid data for strategy execution');

        $invalidStrategy = new MockStrategy(true, null, false);
        $this->context->addStrategy($invalidStrategy);
        
        $this->context->executeStrategy(['input' => 'data']);
    }

    public function testExecuteStrategyUsesFirstMatchingStrategy(): void
    {
        $expectedData = ['first' => 'match'];
        $firstStrategy = new MockStrategy(true, $expectedData);
        $secondStrategy = new MockStrategy(true, ['second' => 'match']);

        $this->context->addStrategy($firstStrategy)
                     ->addStrategy($secondStrategy);

        $result = $this->context->executeStrategy(['input' => 'data']);

        $this->assertSame($expectedData, $result);
    }

    public function testExecuteStrategyWithConfiguration(): void
    {
        $strategy = new MockStrategy();
        $config = ['key' => 'value'];
        
        $this->context->addStrategy($strategy, $config);
        $result = $this->context->executeStrategy(['input' => 'data']);

        $this->assertSame(['input' => 'data'], $result);
    }

    public function testExecuteStrategyWithMultipleNonMatchingStrategies(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No suitable strategy found for the given data');

        $firstStrategy = new MockStrategy(false);
        $secondStrategy = new MockStrategy(false);
        $thirdStrategy = new MockStrategy(false);

        $this->context->addStrategy($firstStrategy)
                     ->addStrategy($secondStrategy)
                     ->addStrategy($thirdStrategy);

        $this->context->executeStrategy(['input' => 'data']);
    }
}
