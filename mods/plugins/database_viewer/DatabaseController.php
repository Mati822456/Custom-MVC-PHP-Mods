<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Repository\PluginRepository;
use App\Repository\SettingRepository;
use App\Repository\ThemeRepository;

class DatabaseController extends Controller{

    private $pluginRepository;
    private $themeRepository;
    private $settingRepository;

    public function __construct()
    {
        parent::__construct();
        $this->pluginRepository = new PluginRepository;
        $this->themeRepository = new ThemeRepository;
        $this->settingRepository = new SettingRepository;
    }

    public function index()
    {

        $plugins = $this->pluginRepository->getAll();

        $themes = $this->themeRepository->getAll();

        $settings = $this->settingRepository->getAll();

        $this->router->render('database_viewer\\index', [
            'plugins' => $plugins,
            'themes' => $themes,
            'settings' => $settings
        ]);
    }

}