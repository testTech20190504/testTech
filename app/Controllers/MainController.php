<?php

namespace App\Controllers;

use App;
use App\Components\Auth\Auth;
use \Twig_Loader_Filesystem;
use \Twig_Environment;

class MainController
{
    /** @var Twig_Environment */
    protected $twig;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->loader = new \Twig_Loader_Filesystem(ROOT . '/app/Views');
        $this->twig   = new \Twig_Environment($this->loader);
        $this->auth   = new Auth(App::getInstance()->getDatabase());
        $this->twig->addGlobal('session', $_SESSION);
    }

    /**
     * Méthode de chargement de model
     * @param $model
     */
    protected function loadModel($model)
    {
        $this->$model = App::getInstance()->getModel($model);
    }

    /**
     * @param $method
     * @param array $datas
     * @return mixed
     */
    public function apiClient($method, $data = [])
    {
        // on passe par le localhost pour les calls API en interne
        $api = 'localhost/api/' . $method;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $api);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $return = curl_exec($curl);

        if (curl_errno($curl)) {
            error_log(sprintf('Curl error: %s %s %s', $method, json_encode($data), curl_error($curl)));
        }

        curl_close($curl);

        return $return;
    }
}