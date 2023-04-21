<?php

namespace App\Controllers;

use App\Controllers\Controller;

class MainController extends Controller{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->router->render('index');
    }

}