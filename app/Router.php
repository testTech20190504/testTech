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
                '/ajax/email',
                '/api/email',
                '/ajax/palindrome',
                '/api/palindrome',

                '/contact/index',
                '/contact/add',
            ];
        }

        return $routes;
    }

    /**
     * Resolution de la requete http
     */
    public static function dispatch(): void
    {
        $request = new Request();

        $request->setRoute($_SERVER['REQUEST_URI']);
        $request->setHttpVerb($_SERVER['REQUEST_METHOD']);

        try {
            $request->matchRoute(self::getRoutes());
            $request->resolveRoute();
        } catch (Exception $e) {
            $request->ressourceNotFoundDispatch();
        }
    }
}
