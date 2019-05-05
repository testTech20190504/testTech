<?php

namespace App\Components\Auth;

use App\Database;
use App\Components\Auth\AbstractAuth;

class Auth extends AbstractAuth
{
    /**
     * Mathode de connexion de l'utilisateur a partir de son login et du mot de passe encoder en md5
     *
     * @param $login
     * @param $password
     *
     * @return boolean
     */
    public function login($login, $password)
    {
        $user = $this->database->prepare('SELECT * FROM users WHERE login = ?',
            [$login], null, true);
        if ($user) {
            if ($user->password === md5($password)) {
                $_SESSION['auth'] = ["id"    => $user->id,
                                     "login" => $user->login,
                                     "email" => $user->email
                ];
                return true;
            }
        }
        return false;
    }
}