<?php

namespace App\Controllers;

use App\Router;
use App\Database;
use App\Controllers\Manager;

abstract class Controller{

    protected Router $router;
    protected Database $database;
    protected Manager $manager;

    function __construct()
    {
        $this->router = new Router();
        $this->database = new Database();
        $this->manager = new Manager();
    }

}