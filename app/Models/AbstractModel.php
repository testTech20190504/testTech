<?php

namespace App\Models;

use App\Database;

abstract class AbstractModel
{
    /**
     * @var string
     */
    protected $model;

    /**
     * @var Database
     */
    protected $database;

    /**
     * Model constructor.
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;

        if (is_null($this->model)) {
            $tab = explode('\\', get_class($this));
            $class = end($tab);
            $this->model = strtolower(str_replace('Model', '', $class)) . 's';
        }
    }

    /**
     * @return array|bool|false|mixed|\PDOStatement
     */
    public function getAll()
    {
        return $this->query('SELECT * FROM ' . $this->model);
    }

    /**
     * @param $id
     * @return array|bool|false|mixed|\PDOStatement
     */
    public function findById($id)
    {
        return $this->query("SELECT * FROM {$this->model} WHERE id = ?", [$id], true);
    }

    /**
     * @param $statement
     * @param null $attributes
     * @param bool $one
     * @return array|bool|false|mixed|\PDOStatement
     */
    public function query($statement, $attributes = null, $one = false)
    {
        if ($attributes) {
            return $this->database->prepare(
                $statement,
                $attributes,
                null,
                $one
            );
        } else {
            return $this->database->query(
                $statement,
                null,
                $one
            );
        }
    }

    /**
     * @param string $statment
     * @param array $fields
     * @param int $id
     *
     * @return array|bool|mixed|\PDOStatement
     */
    private function prepareQueryWithAttributes(string $statement, array $fields, $id = null)
    {
        $fields = $this->cleanInputs($fields);

        $sqlParts = [];
        $attributes = [];

        foreach ($fields as $k => $v) {
            $sqlParts[] = "$k = ?";
            $attributes[] = $v;
        }

        $sqlPart = implode(', ', $sqlParts);

        if ($statement === 'create') {
            $query = "INSERT INTO {$this->table} SET $sqlPart";
        } else {
            $query = "UPDATE {$this->table} SET $sqlPart WHERE id = $id";
        }

        return $this->query($query, $attributes, true);
    }

    /**
     * @param $fields
     *
     * @return array|bool|mixed|\PDOStatement
     */
    public function create($fields)
    {
        return $this->prepareQueryWithAttributes('create', $fields);
    }

    /**
     * Update statement
     *
     * @param int $id
     * @param array $fields
     */
    public function update(int $id, array $fields)
    {
        return $this->prepareQueryWithAttributes('update', $fields, $id);
    }

    /**
     * Supprime un enregistrement
     *
     * @param $id
     * @return array|bool|false|mixed|\PDOStatement
     */
    public function delete($id)
    {
        return $this->query("DELETE FROM {$this->table} WHERE id = ?", [$id],
            true);
    }

    /**
     *
     * @param $data
     *
     * @return array|string
     */
    private function cleanInputs($data)
    {
        $cleanInputs = [];

        if (is_array($data)) {

            foreach ($data as $k => $v) {
                $cleanInputs[$k] = $this->cleanInputs($v);
            }

        } else {
            $data = htmlspecialchars($data);
            $data = strip_tags($data);
            $cleanInputs = trim($data);
        }

        return $cleanInputs;
    }
}