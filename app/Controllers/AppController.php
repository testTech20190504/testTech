<?php

namespace App\Controllers;

use App\Controllers\MainController;
use Components\Auth\auth;

class AppController extends MainController
{
    /**
     * AppController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $app = App::getInstance();
        $auth = new auth($app->getDatabase());
        if (!$auth->logged()) {
            $this->forbidden();
        }
    }

    /**
     * Méthode de redirection d'un utilisateur vers la page de login
     */
    protected function forbidden()
    {
        header('HTTP/1.0 403 Forbidden');
        header('Location: index.php?p=user.login');
    }

    /**
     * Méthode de chargement de model
     * @param $model
     */
    protected function loadModel($model)
    {
        $this->$model = App::getInstance()->getModel($model);
    }
}