<?php

declare(strict_types = 1);

namespace Example\Controller;

use Example\Model\ExampleModel;
use Example\View\ExampleView;
use Exception;
use Mini\Controller\Controller;
use Mini\Controller\Exception\BadInputException;
use Mini\Http\Request;

/**
 * Example entrypoint logic.
 */
class ExampleController extends Controller
{
    /**
     * Example view model.
     * 
     * @var Example\Model\ExampleModel|null
     */
    protected $model = null;

    /**
     * Example view builder.
     * 
     * @var Example\View\ExampleView|null
     */
    protected $view = null;

    /**
     * Setup.
     * 
     * @param ExampleModel $model example data
     * @param ExampleView  $view  example view builder
     */
    public function __construct(ExampleModel $model, ExampleView $view)
    {
        $this->model = $model;
        $this->view  = $view;
    }

    /**
     * Create an example and display its data.
     *
     * @param Request $request http request
     * @return string view template
     * @throws BadInputException | Exception
     */
    public function createExample(Request $request): string
    {
        $this->model->code = $request->request->get('code');
        $this->model->description = $request->request->get('description');

        $result = $this->model->save();

        if (!$result) {
            if ($this->model->hasError()) {
                $errors = $this->model->getErrors();

                foreach (['code', 'description'] as $field) {
                    if (isset($errors[$field]) && is_array($errors[$field])) {
                        throw new BadInputException($errors[$field][0]);
                    }
                }
            } else {
                throw new Exception("Unable to create record", 500);
            }
        }

        return $this->view->get($this->model);
    }
}
