<?php

namespace App;

use \PDO;

class Database
{
    /** @var string $dbName */
    private $dbName;
    /** @var string $dbUser */
    private $dbUser;
    /** @var string $dbPass */
    private $dbPass;
    /** @var string $dbHost */
    private $dbHost;
    /** @var  $pdo */
    private $pdo;

    /**
     * Database constructor.
     * @param $dbName
     * @param string $dbUser
     * @param string $dbPass
     * @param string $dbHost
     */
    public function __construct(
        $dbName,
        $dbUser = 'root',
        $dbPass = 'root',
        $dbHost = 'localhost'
    ) {
        $this->dbName = $dbName;
        $this->dbUser = $dbUser;
        $this->dbPass = $dbPass;
        $this->dbHost = $dbHost;
    }

    /**
     * set de l'instance PDO
     */
    private function setPDO()
    {
        $pdo = new PDO('mysql:dbname=' . $this->dbName . ';host='
            . $this->dbHost, $this->dbUser, $this->dbPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo = $pdo;
    }

    /**
     * @return PDO
     */
    private function getPDO()
    {
        if ($this->pdo === null) {
            $this->setPDO();
        }

        return $this->pdo;
    }

    /**
     * @param $statement
     * @param null $class_name
     * @param bool $one
     * @return array|false|mixed|\PDOStatement
     */
    public function query($statement, $class_name = null, $one = false)
    {
        $req = $this->getPDO()->query($statement);
        if (
            strpos($statement, 'UPDATE') === 0
            || strpos($statement, 'INSERT') === 0
            || strpos($statement, 'DELETE') === 0
        ) {
            return $req;
        }
        if ($class_name === null) {
            $req->setFetchMode(PDO::FETCH_OBJ);
        } else {
            $req->setFetchMode(PDO::FETCH_CLASS, $class_name);
        }
        if ($one) {
            $datas = $req->fetch();
        } else {
            $datas = $req->fetchAll();
        }
        return $datas;
    }

    /**
     * @param $statement
     * @param $attributes
     * @param null $class_name
     * @param bool $one
     * @return array|bool|mixed
     */
    public function prepare(
        $statement,
        $attributes,
        $class_name = null,
        $one = false
    ) {
        $req = $this->getPDO()->prepare($statement);
        $res = $req->execute($attributes);
        if (
            strpos($statement, 'UPDATE') === 0
            || strpos($statement, 'INSERT') === 0
            || strpos($statement, 'DELETE') === 0
        ) {
            return $res;
        }
        if ($class_name === null) {
            $req->setFetchMode(PDO::FETCH_OBJ);
        } else {
            $req->setFetchMode(PDO::FETCH_CLASS, $class_name);
        }
        if ($one) {
            $datas = $req->fetch();
        } else {
            $datas = $req->fetchAll();
        }
        return $datas;
    }

    /**
     * @return string
     */
    public function lastInsertId()
    {
        return $this->getPDO()->lastInsertId();
    }
}