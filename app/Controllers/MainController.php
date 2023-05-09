<?php

namespace App\Controllers;

class MainController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->router->render('index');
    }
}
