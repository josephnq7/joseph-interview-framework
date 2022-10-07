<?php

declare(strict_types = 1);

namespace Example\Tests\Unit\Controller;

use Example\Tests\BaseCase;
use Mini\Controller\Exception\BadInputException;
use Mini\Http\Request;
use Mini\Util\DateTime;

/**
 * Example entrypoint logic test.
 */
class CalculatorControllerTest extends BaseCase
{
    /**
     * Test add 2 numbers
     * 
     * @return void
     */
    public function testAdd2Numbers(): void
    {

        $request = new Request([], [
            'num1' => '2',
            'num2' => '3',
            'operator' => '+'
        ]);

        $response = $this->getClass('Example\Controller\CalculatorController')->do($request);

        $this->assertNotEmpty($response);
        $this->assertIsString($response);

        // Look for the newly created example
        $this->assertStringContainsString('5', $response);
    }

    /**
     * Test add 2 numbers with missing num2
     * 
     * @return void
     */
    public function testAdd2NumbersOnMissingNum2(): void
    {
        $this->expectException(BadInputException::class);

        $request = new Request([], ['num1' => '3', 'operator' => '+']);

        $this->getClass('Example\Controller\CalculatorController')->do($request);
    }

    /**
     * Test add 2 numbers with missing operator
     * 
     * @return void
     */
    public function testAdd2NumbersOnMissingOperator(): void
    {
        $this->expectException(BadInputException::class);

        $request = new Request([], [
            'num1' => '2',
            'num2' => '3'
        ]);

        $this->getClass('Example\Controller\CalculatorController')->do($request);
    }
}
