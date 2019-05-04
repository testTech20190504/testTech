<?php

namespace App\Components\Api;

class ApiService
{
    /** @var $request */
    public $request;
    /** @var string $content_type */
    public $content_type = "application/json";
    /** @var string  $methode*/
    public $methode = "";
    /** @var int $code */
    public $code = 200;

    /**
     * ApiService constructor.
     */
    public function __construct()
    {
        $this->inputs();
    }

    /**
     * processApi
     */
    public function processApi()
    {
        $func = strtolower(trim(str_replace("/", "", $this->request['request'])));
        if ((int)method_exists($this, $func) > 0) {
            $this->$func();
        } else {
            $this->response('', 404);
        }
    }

    /**
     * @return mixed
     */
    private function getStatusMessage()
    {
        $status = [
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            406 => 'Not Acceptable',
            404 => 'Not Found',
            500 => 'Internal Server Error'
        ];

        return ($status[$this->code]) ? $status[$this->code] : $status[500];
    }

    /**
     * Récupération de la méthode
     *
     * @return mixed
     */
    public function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Récupération des donnés envoyées
     */
    public function inputs()
    {
        switch ($this->getRequestMethod()) {
            case "POST":
                $this->request = $this->cleanInputs($_POST);
                break;
            case "GET":
            case "DELETE":
                $this->request = $this->cleanInputs($_GET);
                break;
            case "PUT":
                parse_str(file_get_contents("php://input"), $this->request);
                $this->request = $this->cleanInputs($this->request);
                break;
            default:
                $this->response('', 406);
                break;
        }

    }

    /**
     * @param $data
     * @param $status
     */
    public function response($data, $status)
    {
        $this->code = ($status) ? $status : 200;
        $this->setHeader();
        echo $data;
        exit;
    }

    /**
     * Mise à jour de l'entete
     */
    private function setHeader()
    {
        header("HTTP/1.1 " . $this->code . " " . $this->getStatusMessage());
        header("Content-Type:" . $this->_content_type);
    }

    /**
     * @param $data
     * @return array|string
     */
    private function cleanInputs($data)
    {
        $cleanInput = array();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $cleanInput[$k] = $this->cleanInputs($v);
            }
        } else {
            if (get_magic_quotes_gpc()) {
                $data = trim(stripslashes($data));
            }
            $data = strip_tags($data);
            $cleanInput = trim($data);
        }
        return $cleanInput;
    }
}