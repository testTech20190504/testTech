<?php

namespace App\Components\Http;

use \App\Components\Http\HttpRequestException;
use \App\Controllers\ErrorController;
use \Error;
use \Exception;

Class Request
{
    protected $httpVerb;
    protected $route;
    protected $routeParts;

    /**
     * Set route & routeParts
     * @param string $uri
     */
    public function setRoute($uri): void
    {
        // route par default
        $this->route = ($uri === '/') ? '/contact/index' : $uri;
        // @TODO improve this
        $this->routeParts = explode('/', $this->route);
    }

    /**
     * Set httpverb
     * @param string $httpVerb
     */
    public function setHttpVerb($httpVerb): void
    {
        $this->httpVerb = strtolower($httpVerb);
    }

    /**
     * Resolution du match de la route
     * @param string $routes
     * @throws HttpRequestException
     */
    public function matchRoute(array $routes): void
    {
        $route = $this->route;

        if (!in_array($route, $routes)) {
            error_log(sprintf('[ERROR] Request::matchRoute::Route Not Found : %s', $route));

            $this->ressourceNotFoundHeader();
            throw new HttpRequestException('Request::matchRoute::Route not found (Error 404)');
        }
    }

    /**
     * Resolution de la requete
     * @param string $routes
     * @throws HttpRequestException
     */
    public function resolveRequest()
    {
        $controllerName = $this->getControllerName();
        $actionName = $this->getActionName();

        try {
            $controller = new $controllerName();
            $controller->$actionName();
        } catch (Error $e) {
            error_log(sprintf('[ERROR] Request::resolveRoute::%s', $e->getMessage()));

            $this->ressourceNotFoundHeader();
            throw new HttpRequestException('Request::resolveRoute::Ressource Not Found');
        }
    }

    /**
     * Gestion d'une ressource non trouvée
     * @param Exception $e
     */
    public function ressourceNotFoundDispatch(Exception $e): void
    {
        switch ($this->httpVerb) {
            case 'get':
                $controller = new ErrorController();
                $controller->error($e);
                break;

            default:
                //  ne rien faire
        }
    }

    /**
     * Header 404 error
     */
    public function ressourceNotFoundHeader(): void
    {
        header('HTTP/1.0 404 Not Found');
    }

    /**
     * ControllerName
     * @return string
     */
    public function getControllerName(): string
    {
        $controllerName = ucfirst($this->routeParts[1]);
        return sprintf('\App\Controllers\\%sController', $controllerName);
    }

    /**
     * ActionName
     * @return string
     */
    public function getActionName(): string
    {
        return $this->routeParts[2];
    }
}
