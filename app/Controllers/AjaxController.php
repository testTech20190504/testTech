<?php

namespace App\Controllers;

use App\Controllers\MainController;
use App\Components\Api\Api;

class AjaxController extends MainController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Endpoint pour call ajax palindrome
     */
    public function palindrome()
    {
        // @TODO sanitize POST data
        $isPalindrome = $this->apiClient('palindrome', $_POST);
        echo $isPalindrome;
    }

    /**
     * Endpoint pour call ajax email
     */
    public function email()
    {
        // @TODO sanitize POST data
        $isEmailValid = $this->apiClient('email', $_POST);
        echo $isEmailValid;
    }
}
