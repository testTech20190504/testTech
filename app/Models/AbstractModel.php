<?php

namespace App\Models;

use App\Database;

abstract class AbstractModel
{
    /** @var string  */
    protected $model;
    /** @var Database  */
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
     * @param $fields
     *
     * @return array|bool|mixed|\PDOStatement
     */
    public function create($fields)
    {
        $fields = $this->cleanInputs($fields);
        $sqlParts = [];
        $attributes = [];
        foreach ($fields as $k => $v) {
            $sqlParts[] = "$k = ?";
            $attributes[] = $v;
        }
        $sqlPart = implode(', ', $sqlParts);
        return $this->query("INSERT INTO {$this->table} SET $sqlPart",
            $attributes, true);
    }

    /**
     * @param $id
     * @param $fields
     */
    public function update($id, $fields)
    {
        //@todo
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
            if (get_magic_quotes_gpc()) {
                $data = trim(stripslashes($data));
            }
            $data = strip_tags($data);
            $cleanInputs = trim($data);
        }

        return $cleanInputs;
    }
}