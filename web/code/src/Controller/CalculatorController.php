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
        $calculator = new Calculator();

        $calculator->setNum1($request->request->get('num1'));
        $calculator->setNum2($request->request->get('num2'));
        $calculator->setOperator($request->request->get('operator'));

        $calculator->validate();

        $result = $calculator->calc();

        return view('app/calculator/result', ['result' => $result]);
    }
}
