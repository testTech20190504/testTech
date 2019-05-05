<?php

namespace App\Controllers;

use App\Components\Auth\Auth;
use App\Controllers\MainController;
use App\Database;

class UserController extends MainController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function login()
    {
        $errors = false;

        if (!empty($_POST)) {

            $auth = new Auth(new Database());

            if ($auth->login($_POST['login'], $_POST['password'])) {
                header('Location: /contact/index');
            } else {
                $errors = true;
            }
        }

        echo $this->twig->render('login.html.twig', ['errors' => $errors]);
    }
}