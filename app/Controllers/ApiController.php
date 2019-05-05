<?php

namespace App\Controllers;

use App\Controllers\MainController;
use App\Components\Api\Api;

class ApiController extends MainController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        // @TODO ajouter un test sur un access_token
    }

    /**
     * Endpoint pour API call email verification
     */
    public function palindrome()
    {
        $api = new Api();
        $api->request['request'] = 'palindrome';
        return $api->processApi();
    }

    /**
     * Endpoint pour API call email verification
     */
    public function email()
    {
        $api = new Api();
        $api->request['request'] = 'email';
        return $api->processApi();
    }
}
