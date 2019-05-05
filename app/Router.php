<?php

use \App\Components\Http\Request;
use \App\Controllers\ContactController;

Class Router
{
    /**
     * liste des routes de l' application
     * @return array
     */
    public static function getRoutes(): array
    {
        static $routes;

        if (!isset($routes)) {
            $routes = [
                'ajax/email',
                'api/email',
                'ajax/palindrome',
                'api/palindrome',

                'contact/index',
                'contact/add',
                'contact/edit',

                'user/login',
                'error/error',
            ];
        }

        return $routes;
    }

    /**
     * Resolution de la requete http
     */
    public static function dispatch(): void
    {
        try {
            $request = new Request();

            $request->setHttpVerb($_SERVER['REQUEST_METHOD']);
            $request->setRoute($_SERVER['REQUEST_URI']);

            $request->matchRoute(self::getRoutes());
            $request->resolveRequest();

        } catch (Exception $e) {
            $request->ressourceNotFoundDispatch($e);
        }
    }
}
