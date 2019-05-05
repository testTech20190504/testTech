<?php

namespace App\Controllers;

use App\Controllers\MainController;
use Exception;

class ErrorController extends MainController
{
    /**
     * @param Exception $e
     */
    public function error(Exception $e)
    {
        echo $this->twig->render('error.html.twig', ['errorMessage' => $e->getMessage()]);
    }
}
