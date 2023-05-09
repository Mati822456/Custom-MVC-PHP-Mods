<?php

namespace App\Controllers;

use App\Repository\PluginRepository;
use App\Repository\ThemeRepository;

class ModsCountController extends Controller
{
    private $pluginRepository;
    private $themeRepository;

    public function __construct()
    {
        parent::__construct();
        $this->pluginRepository = new PluginRepository();
        $this->themeRepository = new ThemeRepository();
    }

    public function index()
    {
        $activatedPlugins = $this->pluginRepository->getAll();
        $activatedThemes = $this->themeRepository->getAll();

        $modsCount = [
            'plugins' => count($activatedPlugins),
            'themes'  => count($activatedThemes),
        ];

        echo json_encode($modsCount);
    }
}
