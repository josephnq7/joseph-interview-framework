<?php

namespace Example\Model;

use Exception;
use Mini\Model\Model;

abstract class TableModel extends Model
{
    /**
     * @var array $fields table fields
     */
    protected $fields = [];

    /** @var array $data */
    private $data = [];

    protected $errors = [];

    public function __construct()
    {
        parent::__construct();

        if (is_array($this->fields)) {
            foreach ($this->fields as $field) {
                $this->data[$field] = '';
            }
        }
    }

    /**
     * @param $field
     * @param $value
     * @throws Exception
     */
    public function __set($field, $value)
    {
        if (isset($this->data[$field])) {
            $value = is_null($value) ? '' : trim($value);
            $this->data[$field] = $value;
        } else {
            throw new Exception("Field: $field does not exist", 500);
        }
    }

    /**
     * @param $field
     * @return mixed
     * @throws Exception
     */
    public function __get($field)
    {
        if (isset($this->data[$field])) {
            return $this->data[$field];
        } else {
            throw new Exception("Field: $field does not exist", 500);
        }
    }

    public function __isset($field)
    {
        return isset($this->data[$field]);
    }

    public function __unset($field)
    {
        unset($this->data[$field]);
    }

    /**
     * @param string $field
     * @return bool
     */
    public function hasProperty(string $field) : bool
    {
        return isset($this->data[$field]);
    }

    /**
     * @param string|null $field
     * @return array
     */
    public function getErrors(?string $field = null) : array
    {
        if ($field) {
            $errors = isset($this->errors[$field]) ? [$field => $this->errors[$field]] : [];
        } else {
            $errors = $this->errors;
        }
        return $errors;
    }

    /**
     * @return bool
     */
    public function hasError() : bool
    {
        return count($this->errors);
    }


    /**
     * @param string $field
     * @param string $error
     */
    public function addError(string $field, string $error)
    {
        if (!empty($error)) {
            $this->errors[$field][] = $error;
        }
    }

}