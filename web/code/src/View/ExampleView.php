<?php

declare(strict_types = 1);

namespace Example\View;

use Example\Model\ExampleModel;
use Mini\Controller\Exception\BadInputException;

/**
 * Example view builder.
 */
class ExampleView
{
    /**
     * Example data.
     * 
     * @var Example\Model\ExampleModel|null
     */
    protected $model = null;

    /**
     * Setup.
     * 
     * @param ExampleModel $model example data
     */
    public function __construct(ExampleModel $model)
    {
        $this->model = $model;
    }

    /**
     * @param ExampleModel $example
     * @return string
     * @throws BadInputException
     */
    public function get(ExampleModel $example): string
    {
        $this->model = $example;
        if (empty($this->model->id) || !$this->model->validate()) {
            throw new BadInputException('Example data is not valid');
        }
        return view('app/example/detail', ['model' => $this->model]);
    }
}
