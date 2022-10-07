<?php

declare(strict_types = 1);

namespace Example\Controller;

use Example\Model\Calculator;
use Mini\Controller\Controller;
use Mini\Controller\Exception\BadInputException;
use Mini\Http\Request;

/**
 * Home entrypoint logic.
 */
class CalculatorController extends Controller
{

    /**
     * @var Calculator|null
     */
    protected $model = null;

    /**
     * Setup.
     *
     * @param Calculator $model
     */
    public function __construct(Calculator $model)
    {
        $this->model = $model;
    }

    /**
     * Show the default page.
     * 
     * @param Request $request http request
     * 
     * @return string view template
     */
    public function index(Request $request): string
    {
        return view('app/calculator/index', ['version' => getenv('APP_VERSION')]);
    }

    /**
     * Create an example and display its data.
     *
     * @param Request $request http request
     * @return string view template
     * @throws BadInputException
     */
    public function do(Request $request): string
    {
        $this->model->setNum1($request->request->get('num1'));
        $this->model->setNum2($request->request->get('num2'));
        $this->model->setOperator($request->request->get('operator'));

        $this->model->validate();
        $result = $this->model->calc();

        return view('app/calculator/result', ['result' => $result]);
    }
}
