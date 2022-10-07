<?php

declare(strict_types = 1);

namespace Example\Tests\Functional\Controller;

use Example\Tests\BaseCase;

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
        $curl = $this->curl();

        // Send the request to the server
        $response = $curl->init(getenv('TEST_URL') . '/calculator/do')
            ->setPost([
                          'num1' => '2.34',
                          'num2' => '3.37',
                          'operator' => '+'
                      ])
            ->send(false);

        $this->assertSame($curl->getStatusCode(), 200);
        $this->assertNull($curl->getError());
        $this->assertNotEmpty($response);
        $this->assertIsString($response);

        // Look for the newly created example
        $this->assertStringContainsString('5.71', $response);
    }

    /**
     * Test add 2 numbers with missing num2
     *
     * @return void
     */
    public function testAdd2NumbersOnMissingNum2(): void
    {
        $curl = $this->curl();

        // Send the request to the server
        $response = $curl->init(getenv('TEST_URL') . '/calculator/do')
            ->setPost(['num1' => '3', 'operator' => '+'])
            ->send();

        $this->assertSame($curl->getStatusCode(), 400);
        $this->assertNull($curl->getError());
        $this->assertNotEmpty($response);
        $this->assertSame('Num2 is not a number', $response['message']);
    }

    /**
     * Test add 2 numbers with missing operator
     *
     * @return void
     */
    public function testAdd2NumbersOnMissingOperator(): void
    {
        $curl = $this->curl();

        // Send the request to the server
        $response = $curl->init(getenv('TEST_URL') . '/calculator/do')
            ->setPost([
                          'num1' => '2',
                          'num2' => '3',
                      ])
            ->send();

        $this->assertSame($curl->getStatusCode(), 400);
        $this->assertNull($curl->getError());
        $this->assertNotEmpty($response);
        $this->assertSame('Operator  is not supported', $response['message']);
    }
}
