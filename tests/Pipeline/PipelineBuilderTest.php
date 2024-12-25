<?php

declare(strict_types=1);

namespace Tests\Pipeline;

use DesiredPatterns\Pipeline\PipelineBuilder;
use PHPUnit\Framework\TestCase;

class PipelineBuilderTest extends TestCase
{
    private PipelineBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = new PipelineBuilder();
    }

    public function testBasicBuilder(): void
    {
        $result = $this->builder
            ->add(fn($x) => $x * 2)
            ->add(fn($x) => $x + 1)
            ->build(5)
            ->get();

        $this->assertEquals(11, $result);
    }

    public function testBuilderWithErrorHandling(): void
    {
        $result = $this->builder
            ->withErrorHandling(fn(\Throwable $e) => 'error')
            ->add(fn($x) => $x / 0)
            ->build(5)
            ->get();

        $this->assertEquals('error', $result);
    }

    public function testBuilderWithMultipleErrorHandlers(): void
    {
        $result = $this->builder
            ->withErrorHandling(fn(\Throwable $e) => throw new \RuntimeException('First handler failed'))
            ->withErrorHandling(fn(\Throwable $e) => 'second handler')
            ->add(fn($x) => $x / 0)
            ->build(5)
            ->get();

        $this->assertEquals('second handler', $result);
    }

    public function testBuilderWithValidation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Value must be greater than 10');

        $this->builder
            ->withValidation(fn($x) => $x > 10, 'Value must be greater than 10')
            ->build(5)
            ->get();
    }

    public function testBuilderWithMultipleValidations(): void
    {
        $result = $this->builder
            ->withValidation(fn($x) => $x > 0, 'Must be positive')
            ->withValidation(fn($x) => $x < 10, 'Must be less than 10')
            ->withErrorHandling(fn(\Throwable $e) => 'validation error')
            ->build(5)
            ->get();

        $this->assertEquals(5, $result);

        $result = $this->builder
            ->withValidation(fn($x) => $x > 0, 'Must be positive')
            ->withValidation(fn($x) => $x < 10, 'Must be less than 10')
            ->withErrorHandling(fn(\Throwable $e) => 'validation error')
            ->build(15)
            ->get();

        $this->assertEquals('validation error', $result);
    }

    public function testBuilderWithValidationAndErrorHandling(): void
    {
        $result = $this->builder
            ->withValidation(fn($x) => $x > 0, 'Must be positive')
            ->withErrorHandling(fn(\Throwable $e) => 'caught error')
            ->add(fn($x) => $x / 0)
            ->build(5)
            ->get();

        $this->assertEquals('caught error', $result);

        $result = $this->builder
            ->withValidation(fn($x) => $x > 0, 'Must be positive')
            ->withErrorHandling(fn(\Throwable $e) => 'caught error')
            ->add(fn($x) => $x / 0)
            ->build(-5)
            ->get();

        $this->assertEquals('caught error', $result);
    }

    public function testBuilderWithChainedOperations(): void
    {
        $result = $this->builder
            ->add(fn($x) => $x * 2)
            ->add(fn($x) => $x + 1)
            ->add(fn($x) => "Result: $x")
            ->build(5)
            ->get();

        $this->assertEquals('Result: 11', $result);
    }

    public function testBuilderWithEmptyPipeline(): void
    {
        $result = $this->builder
            ->build(5)
            ->get();

        $this->assertEquals(5, $result);
    }

    public function testBuilderWithNullValue(): void
    {
        $result = $this->builder
            ->add(fn($x) => $x === null ? 'was null' : 'not null')
            ->build(null)
            ->get();

        $this->assertEquals('was null', $result);
    }

    public function testBuilderWithTypeValidation(): void
    {
        $result = $this->builder
            ->add(fn($x) => (string)$x)
            ->withValidation(fn($x) => is_string($x), 'Must be string')
            ->withErrorHandling(fn(\Throwable $e) => 'type error')
            ->build(42)
            ->get();

        $this->assertEquals('42', $result);
    }

    public function testBuilderWithNestedErrorHandling(): void
    {
        $result = $this->builder
            ->withErrorHandling(fn(\Throwable $e) => 'outer error')
            ->add(function($x) {
                try {
                    return $x / 0;
                } catch (\Throwable $e) {
                    throw new \RuntimeException('Inner error');
                }
            })
            ->build(5)
            ->get();

        $this->assertEquals('outer error', $result);
    }

    public function testBuilderWithContextPassing(): void
    {
        $context = [];
        
        $result = $this->builder
            ->add(function($x) use (&$context) {
                $context['step1'] = $x * 2;
                return $context['step1'];
            })
            ->add(function($x) use (&$context) {
                $context['step2'] = $x + 1;
                return $context['step2'];
            })
            ->build(5)
            ->get();

        $this->assertEquals(11, $result);
        $this->assertEquals(10, $context['step1']);
        $this->assertEquals(11, $context['step2']);
    }

    public function testBuilderWithArrayOperations(): void
    {
        $result = $this->builder
            ->add(fn($arr) => array_map(fn($x) => $x * 2, $arr))
            ->add(fn($arr) => array_sum($arr))
            ->add(fn($x) => $x / 2)
            ->build([1, 2, 3])
            ->get();

        $this->assertEquals(6, $result);
    }

    public function testBuilderWithMixedOperations(): void
    {
        $result = $this->builder
            ->withValidation(fn($x) => $x > 0, 'Must be positive')
            ->add(fn($x) => $x * 2)
            ->withErrorHandling(fn(\Throwable $e) => 0)
            ->add(fn($x) => sqrt($x))
            ->build(5)
            ->get();

        $this->assertEquals(sqrt(10), $result);
    }

    public function testBuilderWithConditionalValidation(): void
    {
        $result1 = $this->builder
            ->withValidation(fn($x) => $x > 0, 'Must be positive')
            ->add(fn($x) => $x * 2)
            ->build(5)
            ->get();

        $this->assertEquals(10, $result1);

        $this->expectException(\InvalidArgumentException::class);
        
        $this->builder
            ->withValidation(fn($x) => $x > 0, 'Must be positive')
            ->add(fn($x) => $x * 2)
            ->build(-5)
            ->get();
    }
}
