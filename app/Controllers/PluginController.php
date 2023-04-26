<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Repository\PluginRepository;

class PluginController extends Controller{

    private $pluginRepository;

    public function __construct()
    {
        parent::__construct();
        $this->pluginRepository = new PluginRepository;
    }

    public function index()
    {
        // Fetches the list of all available plugins
        $plugins = $this->manager->listPlugins();

        // Retrieves all plugin data from the plugins Repository
        $result = $this->pluginRepository->getAll();
        $active = [];

        // Create an array with the names of the active plugins
        foreach($result as $plugin){
            $active[] = $plugin->getName();
        }
        
        // Render the "plugins" view, passing the available plugins, the active plugins to the view and plugins that cannot be run
        $this->router->render('plugins', [
            'plugins' => $plugins,
            'active' => $active,
            'cannotRun' => $this->manager->getModsCannotRun()
        ]);

    }

}