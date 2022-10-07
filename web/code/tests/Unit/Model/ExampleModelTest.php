<?php

namespace Example\Tests\Unit\Model;

use Example\Model\ExampleModel;
use Example\Tests\BaseCase;
use Example\Tests\Traits\FakerTrait;
use Mini\Util\DateTime;

class ExampleModelTest extends BaseCase
{
    use FakerTrait;
    /**
     * Test find by id.
     *
     * @return void
     */
    public function testFindById(): void
    {
        $this->mockDatabaseGetProcess();

        $example = ExampleModel::findById(1);

        $this->assertEquals(true, $example instanceof ExampleModel);

        // Look for the newly created example
        $this->assertStringContainsString('TESTCODE', $example->code);
        $this->assertStringContainsString('Test description', $example->description);
    }

    /**
     * Test create Example record
     *
     * @return void
     */
    public function testCreateExampleRecord() : void
    {
        $this->mockDatabaseCreateProcess();

        $example = new ExampleModel();
        $example->code = 'TESTCODE';
        $example->description = 'Test description';
        $result = $example->save();

        $this->assertEquals(true, $result);
        $this->assertEquals(1, $example->id);
        $this->assertEquals(false, $example->hasError());

    }

    public function testValidate() : void
    {
        $this->setUpFaker();
        $example = new ExampleModel();
        $result = $example->validate();
        $this->assertEquals(false, $result);
        $this->assertEquals(true, $example->hasError());

        $this->assertStringContainsString('code is required', print_r($example->getErrors('code'), true));
        $this->assertStringContainsString('description is required', print_r($example->getErrors('description'), true));

        $example = new ExampleModel();
        $example->code = $this->fakeString(51, "N");
        $example->description = $this->fakeString(256, "N");
        $result = $example->validate();
        $this->assertEquals(false, $result);

        $this->assertStringContainsString('code is over 50 characters', print_r($example->getErrors(), true));
        $this->assertStringContainsString('description is over 255 characters', print_r($example->getErrors(), true));
    }

    protected function mockDatabaseCreateProcess(): void
    {
        // Override the created column input set by `now()`
        DateTime::setTestNow(DateTime::create(2020, 7, 14, 12, 00, 00));

        $database = $this->getMock('Mini\Database\Database');

        // Setup the database mock
        $database->shouldReceive('statement')
            ->once()
            ->withArgs($this->withDatabaseInput(['2020-07-14 12:00:00', 'TESTCODE', 'Test description']))
            ->andReturn(1);

        $database->shouldReceive('validateAffected')->once();

        $this->setMockDatabase($database);
    }

    /**
     * Mock the database process for the example create endpoint.
     *
     * @return void
     */
    protected function mockDatabaseGetProcess(): void
    {
        $database = $this->getMock('Mini\Database\Database');

        // Setup the database mock
        $database->shouldReceive('select')
            ->once()
            ->withArgs($this->withDatabaseInput([1]))
            ->andReturn([
                            'id'          => 1,
                            'created'     => '2020-07-14 12:00:00',
                            'code'        => 'TESTCODE',
                            'description' => 'Test description'
                        ]);

        $this->setMockDatabase($database);
    }
}