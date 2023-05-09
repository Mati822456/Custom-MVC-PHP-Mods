<?php

namespace App\Controllers;

use App\Database;
use App\Router;

abstract class Controller
{
    protected Router $router;
    protected Database $database;
    protected Manager $manager;

    public function __construct()
    {
        $this->router = new Router();
        $this->database = new Database();
        $this->manager = new Manager();
    }
}
