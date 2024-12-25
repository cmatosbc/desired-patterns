<?php

declare(strict_types=1);

namespace Tests\Pipeline;

use DesiredPatterns\Pipeline\Pipeline;
use PHPUnit\Framework\TestCase;

class PipelineTest extends TestCase
{
    public function testBasicPipelineOperations(): void
    {
        $result = Pipeline::of(5)
            ->pipe(fn($x) => $x * 2)
            ->pipe(fn($x) => $x + 1)
            ->get();

        $this->assertEquals(11, $result);
    }

    public function testPipelineWithArrays(): void
    {
        $result = Pipeline::of([1, 2, 3])
            ->pipe(fn($arr) => array_map(fn($x) => $x * 2, $arr))
            ->pipe(fn($arr) => array_sum($arr))
            ->get();

        $this->assertEquals(12, $result);
    }

    public function testMapOperation(): void
    {
        $input = [1, 2, 3];
        $result = Pipeline::of(0)
            ->pipe(fn() => Pipeline::of($input)->map($input, fn($x) => $x * 2))
            ->get();

        $this->assertEquals([2, 4, 6], $result);
    }

    public function testThroughOperation(): void
    {
        $operations = [
            fn($x) => $x * 2,
            fn($x) => $x + 1,
            fn($x) => "Result: $x"
        ];

        $result = Pipeline::of(5)
            ->through($operations)
            ->get();

        $this->assertEquals('Result: 11', $result);
    }

    public function testTapOperation(): void
    {
        $sideEffect = 0;
        
        $result = Pipeline::of(5)
            ->tap(function($x) use (&$sideEffect) {
                $sideEffect = $x * 2;
            })
            ->get();

        $this->assertEquals(5, $result); // Original value unchanged
        $this->assertEquals(10, $sideEffect); // Side effect occurred
    }

    public function testWhenOperation(): void
    {
        $result1 = Pipeline::of(5)
            ->when(
                fn($x) => $x > 3,
                fn($x) => $x * 2
            )
            ->get();

        $result2 = Pipeline::of(2)
            ->when(
                fn($x) => $x > 3,
                fn($x) => $x * 2
            )
            ->get();

        $this->assertEquals(10, $result1); // Condition met, transformation applied
        $this->assertEquals(2, $result2);  // Condition not met, original value returned
    }

    public function testTryOperation(): void
    {
        $result1 = Pipeline::of(5)
            ->try(
                fn($x) => $x / 1,
                fn(\Throwable $e) => 'error'
            )
            ->get();

        $result2 = Pipeline::of(5)
            ->try(
                fn($x) => $x / 0,
                fn(\Throwable $e) => 'error'
            )
            ->get();

        $this->assertEquals(5, $result1);    // No error
        $this->assertEquals('error', $result2); // Division by zero caught
    }
}
