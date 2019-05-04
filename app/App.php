<?php

use App\Config;
use App\Database;

class App
{
    /** @var  $dbInstance */
    private $dbInstance;
    /** @var  $instance */
    private static $instance;

    /**
     * Auto chargement des classes
     */
    public static function load()
    {
        session_start();
        require ROOT . '/app/Autoloader.php';
        App\Autoloader::register();
        require ROOT . '/vendor/autoload.php';
    }

    /**
     * @return App
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new App();
        }
        return self::$instance;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getModel($name)
    {
        $name = '\\App\\Models\\' . ucfirst($name) . 'Model';
        return new $name($this->getDatabase());
    }

    /**
     * set de la Bdd
     * @param $config
     */
    private function setDatabase($config)
    {
        $this->dbInstance = new Database($config->get('db_name'),
            $config->get('db_user'), $config->get('db_pass'),
            $config->get('db_host'));
    }

    /**
     * @return Database
     */
    public function getDatabase()
    {
        if (is_null($this->dbInstance)) {
            $this->setDatabase(Config::getInstance());
        }

        return $this->dbInstance;
    }
}