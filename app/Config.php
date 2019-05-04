<?php

namespace App;

class Config
{
    /** @var array $settings  */
    private $settings = [];
    /** @var  $instance */
    private static $instance;

    /**
     * @return Config
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Config();
        }

        return self::$instance;
    }

    /**
     * Config constructor.
     */
    public function __construct()
    {
        $this->settings = [
            "db_user" => "newuser",
            "db_pass" => "password",
            "db_host" => "localhost",
            "db_name" => "crm"
        ];
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function get($key)
    {
        if (!isset($this->settings[$key])) {
            return null;
        }
        return $this->settings[$key];
    }
}