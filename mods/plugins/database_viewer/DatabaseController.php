<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Repository\PluginRepository;
use App\Repository\ThemeRepository;

class DatabaseController extends Controller{

    private $pluginRepository;
    private $themeRepository;

    public function __construct()
    {
        parent::__construct();
        $this->pluginRepository = new PluginRepository;
        $this->themeRepository = new ThemeRepository;
    }

    public function index()
    {

        $plugins = $this->pluginRepository->getAll();

        $themes = $this->themeRepository->getAll();

        $this->router->render('database_viewer\\index', [
            'plugins' => $plugins,
            'themes' => $themes
        ]);
    }

}