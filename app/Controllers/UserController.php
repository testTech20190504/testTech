<?php

namespace App\Controllers;

use App;
use App\Components\Auth\Auth;
use App\Controllers\MainController;
use App\Database;
use Exception;

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

            try {
                $auth = new Auth(App::getInstance()->getDatabase());

                if ($auth->login($_POST['login'], $_POST['password'])) {
                    header('Location: /contact/index');
                }
            } catch (Exception $e) {
                //  silent error
            }

            $errors = true;
        }

        echo $this->twig->render('login.html.twig', ['errors' => $errors]);
    }
}