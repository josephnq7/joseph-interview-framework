<?php

namespace Example\Model;

use Mini\Controller\Exception\BadInputException;

/**
 * @property numeric $num1
 * @property numeric $num2
 * @property string $operator
 */
class Calculator
{
    protected $num1;
    protected $num2;
    protected $operator;

    const OPERATOR_PLUS = '+';

    public static $operators = [self::OPERATOR_PLUS];

    /**
     * @throws BadInputException
     */
    public function validate()
    {
        if (!is_numeric($this->num1)) {
            throw new BadInputException("Num1 is not a number");
        }

        if (!is_numeric($this->num2)) {
            throw new BadInputException("Num2 is not a number");
        }

        if (!in_array($this->operator, self::$operators)) {
            throw new BadInputException("Operator {$this->operator} is not supported");
        }
    }

    public function calc()
    {
        $result = 0;
        switch ($this->operator) {
            case self::OPERATOR_PLUS:
                $result = ($this->num1) + ($this->num2);
                break;
            default:
                break;
        }
        return $result;
    }

    /**
     * @return float|int|string
     */
    public function getNum1()
    {
        return $this->num1;
    }

    /**
     * @param float|int|string $num1
     */
    public function setNum1($num1): void
    {
        $this->num1 = $num1;
    }

    /**
     * @return float|int|string
     */
    public function getNum2()
    {
        return $this->num2;
    }

    /**
     * @param float|int|string $num2
     */
    public function setNum2($num2): void
    {
        $this->num2 = $num2;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     */
    public function setOperator(string $operator): void
    {
        $this->operator = $operator;
    }

}