<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Repository\ThemeRepository;

class ThemeController extends Controller{

    private $themeRepository;

    public function __construct()
    {
        parent::__construct();
        $this->themeRepository = new ThemeRepository;
    }

    public function index()
    {
        // Fetches the list of all available themes
        $themes = $this->manager->listThemes();

        // Retrieves all theme data from the themes Repository.
        $result = $this->themeRepository->getAll();
        $active = [];

        // Create an array with the names of the active themes
        foreach($result as $theme){
            $active[] = $theme->getName();
        }
        
        // Render the "themes" view, passing the available themes, the active themes to the view and themes that cannot be run
        $this->router->render('themes', [
            'themes' => $themes,
            'active' => $active,
            'cannotRun' => $this->manager->getModsCannotRun()
        ]);

    }

}