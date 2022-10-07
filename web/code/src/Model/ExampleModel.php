<?php

declare(strict_types=1);

namespace Example\Model;

/**
 * Example Model
 *
 * @property integer $id
 * @property string $created
 * @property string $code
 * @property string $description
 */
class ExampleModel extends TableModel
{
    protected $fields = [
        'id',
        'created',
        'code',
        'description'
    ];

    /**
     * find data by id
     *
     * @param int $id
     * @return ExampleModel
     */
    public static function findById(int $id): ExampleModel
    {
        $obj = new ExampleModel();
        $sql = '
            SELECT
                example_id AS "id",
                created,
                code,
                description
            FROM
                ' . getenv('DB_SCHEMA') . '.master_example
            WHERE
                example_id = ?';

        $db = container('Mini\Database\Database');

        $data = $db->select([
                                'title' => 'Get example data',
                                'sql' => $sql,
                                'inputs' => [$id]
                            ]);

        if ($data) {
            foreach ($data as $field => $value) {
                if ($obj->hasProperty($field)) {
                    $obj->{$field} = $value;
                }
            }
        }

        return $obj;
    }

    /**
     * validate data for fields
     *
     * @return bool
     */
    public function validate() : bool
    {
        foreach ($this->fields as $field) {
            switch ($field) {
                case 'code':
                    if (empty($this->{$field})) {
                        $this->addError($field, "{$field} is required");
                        break;
                    }
                    if (strlen($this->{$field}) > 50) {
                        $this->addError($field, "{$field} is over 50 characters");
                    }
                    break;
                case 'description':
                    if (empty($this->{$field})) {
                        $this->addError($field, "{$field} is required");
                        break;
                    }
                    if (strlen($this->{$field}) > 255) {
                        $this->addError($field, "{$field} is over 255 characters");
                    }
                    break;
                default:
                    break;
            }
        }
        return !$this->hasError();
    }

    /**
     * create Example record
     *
     * @return bool
     */
    public function save() : bool
    {
        $this->created = now();

        if ($this->validate()) {
            $sql = '
            INSERT INTO
                ' . getenv('DB_SCHEMA') . '.master_example
            (
                created,
                code,
                description
            )
            VALUES
            (?,?,?)';

            $id = $this->db->statement([
                           'title' => 'Create example',
                           'sql' => $sql,
                           'inputs' => [
                               (string)$this->created,
                               (string)$this->code,
                               (string)$this->description
                           ]
           ]);

            $this->db->validateAffected();

            $this->id = $id;
            return true;
        }
        return false;
    }
}
