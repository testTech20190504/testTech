<?php

define('ROOT', dirname(__DIR__ . '/..'));
require ROOT . '/app/App.php';
App::load();

require ROOT . '/app/Router.php';
Router::dispatch();
