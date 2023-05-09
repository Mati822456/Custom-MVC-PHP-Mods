<?php

namespace App\Controllers;

use App\Repository\PluginRepository;
use App\Repository\ThemeRepository;

class ShowController extends Controller
{
    private $pluginRepository;
    private $themeRepository;

    public function __construct()
    {
        parent::__construct();
        $this->pluginRepository = new PluginRepository();
        $this->themeRepository = new ThemeRepository();
    }

    public function show()
    {
        $name = strtolower($_GET['name']);

        // Arrays of installed mods
        $plugins = $this->manager->listPlugins();
        $themes = $this->manager->listThemes();

        // Initialize empty variables
        $activated = false;
        $requiredMods = [];
        $incompatibleMods = [];

        // Check if mod with given name exists in the plugins array
        if (array_key_exists($name, $plugins)) {
            $activated = $this->pluginRepository->find(['name' => $name]) ? true : false;
            $mod = $plugins[$name];
        }

        // Check if mod with given name exists in the themes array
        if (array_key_exists($name, $themes)) {
            $activated = $this->themeRepository->find(['name' => $name]) ? true : false;
            $mod = $themes[$name];
        }

        // If a mod exists
        if (!empty($mod)) {
            // Check if the mod has the requirements
            if (isset($mod->requirements)) {
                // Loop through the required mods
                foreach ($mod->requirements as $requirement) {
                    // Check if the required mod exists
                    if (array_key_exists(strtolower($requirement), $plugins) || array_key_exists(strtolower($requirement), $themes)) {
                        $requiredMods[$requirement] = true;
                    } else {
                        $requiredMods[$requirement] = false;
                    }
                }
            }
            // Check if the mod has incompatible mods
            if (isset($mod->incompatible)) {
                // Loop through the incompatible mods
                foreach ($mod->incompatible as $incompatible) {
                    // Check if the incompatible mod is active
                    if ($this->pluginRepository->find(['name' => $incompatible]) || $this->themeRepository->find(['name' => $incompatible])) {
                        $incompatibleMods[$incompatible] = true;
                    } else {
                        $incompatibleMods[$incompatible] = false;
                    }
                }
            }
        } else {
            // If the mod does not exist
            $this->router->render('response', ['code' => 404], 404);
        }

        // Render show.php with the given variables
        $this->router->render('show', [
            'mod'              => $mod,
            'activated'        => $activated,
            'requiredMods'     => $requiredMods,
            'incompatibleMods' => $incompatibleMods,
        ]);
    }
}
