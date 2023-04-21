<?php

namespace App\Controllers;

use \Throwable;
use App\Database;
use App\Router;
use App\Repository\PluginRepository;
use App\Repository\ThemeRepository;
use App\Models\Plugin;
use App\Models\Theme;
use \Exception;

class Manager{

    protected array $Plugins_dir;
    protected array $Themes_dir;
    protected array $plugins;
    protected array $themes;
    protected Router $router;
    protected Database $database;
    private PluginRepository $pluginRepository;
    private ThemeRepository $themeRepository;

    public function __construct()
    {
        // Define the directories where the plugins and themes are stored
        $PluginDir    = './mods/plugins';
        $ThemeDir     = './mods/themes';

        // Scan the plugin and theme directories respectively and return an array of filenames.
        $Plugin_files = scandir($PluginDir);
        $Theme_files = scandir($ThemeDir);

        // Remove unwanted files from the plugin and theme directories.
        $this->Plugins_dir = array_diff($Plugin_files, array('.', '..', 'plugin.php'));
        $this->Themes_dir = array_diff($Theme_files, array('.', '..', 'theme.php'));

        // Initialize empty arrays
        $this->plugins = [];
        $this->themes = [];

        // Create a new instance of the Database class
        $this->database = new Database;

        // Create a new instance of the Router class
        $this->router = new Router();

        // Create a new instances of the Mods classes
        $this->pluginRepository = new PluginRepository;
        $this->themeRepository = new ThemeRepository;

        // Loads the plugins and themes
        $this->load();
       
    }

    private function load()
    {
        // Get all the activated plugins and themes from the database
        $activatedPlugins = $this->pluginRepository->getAll();
        $activatedThemes = $this->themeRepository->getAll();

        // Initialize empty arrays
        $_SESSION['style'] = [];
        $_SESSION['scripts'] = [];
        unset($_SESSION['error']);
        $Plugins_names = [];
        $Themes_names = [];
        
        // Get all the names of the activated plugins and themes.
        foreach($activatedPlugins as $active){
            $Plugins_names[] = strtolower($active->getName());
        }
        foreach($activatedThemes as $active){
            $Themes_names[] = strtolower($active->getName());
        }

        // Loops iterate through all the plugins directories and attempt to include the main PHP file for each plugin
        foreach($this->Plugins_dir as $file){
            try{
                // Includes the main.php file of the plugin indicated by the $file variable
                include_once 'Mods\\Plugins\\' . $file . '\\main.php';

                // Assigns the plugin's namespace and class name to the $className variable
                $className = 'Mods\\Plugins\\' . $file;
                
                // Creates a new instance of the plugin class and assigns it to the $this->plugins array
                $this->plugins[$file] = new $className;
                
                // Check if the directory for the current plugin does not exist in the public folder
                if(!file_exists('./public/mods/plugins/' . $file)){
                    mkdir('./public/mods/plugins/' . $file);
                    copy('./mods/plugins/' . $file. '/'. $this->plugins[$file]->image, './public/mods/plugins/' . $file. '/'. $this->plugins[$file]->image);
                }else{
                    if(!file_exists('./public/mods/plugins/' . $file. '/'. $this->plugins[$file]->image)){
                        copy('./mods/plugins/' . $file. '/'. $this->plugins[$file]->image, './public/mods/plugins/' .  $file. '/'. $this->plugins[$file]->image);
                    }
                }

                // Check if the current version of the application is greater than the supported version of the current plugin
                if(version_compare(APP_VERSION, $this->plugins[$file]->supportedVersion, '>')){
                    $_SESSION['error']['plugin'][] = '"'. $file . '" may not work properly. Supported version: '. $this->plugins[$file]->supportedVersion;
                }

                // Checks if the plugin name ($file) is in the list of activated plugins ($Plugins_names)
                if(in_array(strtolower($file), $Plugins_names)){
                    // If the plugin is activated, calls the run() method of the plugin object
                    $this->plugins[strtolower($file)]->run();
                }

            }catch(Throwable $e){
                // Sets an error message in the $_SESSION array if a plugin fails to load
                $_SESSION['error']['plugin'][] = 'Error while loading "' . $file . '" : ' . $e->getMessage();
            }
        }

        // Loops iterate through all the themes directories and attempt to include the main PHP file for each theme
        foreach($this->Themes_dir as $file){
            try{
                // Includes the main.php file of the theme indicated by the $file variable
                include_once 'Mods\\Themes\\' . $file . '\\main.php';

                // Assigns the theme's namespace and class name to the $className variable
                $className = 'Mods\\Themes\\' . $file;

                // Creates a new instance of the theme class and assigns it to the $this->themes array
                $this->themes[$file] = new $className;

                // Check if the directory for the current theme does not exist in the public folder
                if(!file_exists('./public/mods/themes/' . $file)){
                    mkdir('./public/mods/themes/' . $file);
                    copy('./mods/themes/' . $file. '/'. $this->themes[$file]->image, './public/mods/themes/' . $file. '/'. $this->themes[$file]->image);
                }else{
                    if(!file_exists('./public/mods/themes/' . $file. '/'. $this->themes[$file]->image)){
                        copy('./mods/themes/' . $file. '/'. $this->themes[$file]->image, './public/mods/themes/' . $file. '/'. $this->themes[$file]->image);
                    }
                }

                // Check if the current version of the application is greater than the supported version of the current theme
                if(version_compare(APP_VERSION, $this->themes[$file]->supportedVersion, '>')){
                    $_SESSION['error']['theme'][] = '"'. $file . '" may not work properly. Supported version: '. $this->themes[$file]->supportedVersion;
                }

                // Checks if the theme name ($file) is in the list of activated themes ($Themes_names)
                if(in_array(strtolower($file), $Themes_names)){
                    // If the theme is activated, calls the run() method of the theme object
                    $this->themes[strtolower($file)]->run();
                }

            }catch(Throwable $e){
                // Sets an error message in the $_SESSION array if a theme fails to load
                $_SESSION['error']['theme'][] = 'Error while loading "' . $file . '" : ' . $e->getMessage();
            }
        }

    }

    // Returns an array of loaded plugins.
    public function listPlugins(): Array
    {
        return $this->plugins;
    }

    // Returns an array of loaded themes
    public function listThemes(): Array
    {
        return $this->themes;
    }

    // Activate plugin or theme using url: /activate?name=''&type=''
    public function activate(){
        // Assign the value of 'name' query parameter to the variable $name
        $name = $_GET['name'];

        // Assign the value of 'type' query parameter to the variable $type
        $type = $_GET['type'];
       
        try{
            if($type == 1){ // If variable $type is equal to 1 (plugin)
                if(class_exists('Mods\\Plugins\\' . $name, false)){ // Check if the plugin class exists
                    $this->plugins[strtolower($name)]->run(); // Run the plugin's main method
        
                    // Store the plugin name in the database
                    $plugin = new Plugin;
                    $plugin->setName($name);
                    $this->pluginRepository->store($plugin);
                    
                    $this->router->redirect('/plugin'); // Redirect to the plugin page
                }else{
                    $this->router->render('response', ['code' => 404], 404); // Render the 404 page
                }
            }elseif($type == 2){ // If $type is equal to 2 (theme)
                if(class_exists('Mods\\Themes\\' . $name, false)){ // Check if the theme class exists
                    $this->themes[strtolower($name)]->run(); // Run the theme's main method

                    // Store the theme name in the database
                    $theme = new Theme;
                    $theme->setName($name);
                    $this->themeRepository->store($theme);
                    
                    $this->router->redirect('/theme'); // Redirect to the theme page
                }else{
                    $this->router->render('response', ['code' => 404], 404); // Render the 404 page
                }
            }else{ // If it's not a plugin or theme
                $this->router->render('response', ['code' => 400], 400);
            }
        }catch(Exception $e){
            if($type == 1){ // If $type is equal to 1 (plugin)
                // Set the error message for the plugin page
                $_SESSION['error']['plugin'][] = 'Failed to load plugin: '. $name;
                // Redirect to the plugin page
                $this->router->redirect('/plugin');
            }elseif($type == 2){ // If $type is equal to 2 (theme)
                // Set the error message for the theme page
                $_SESSION['error']['theme'][] = 'Failed to load theme: '. $name;
                // Redirect to the theme page
                $this->router->redirect('/theme');
            }else{ // If it's not a plugin or theme
                $this->router->render('response', ['code' => 400], 400);
            }
        }

    }

    // Deactivate plugin or theme using url: /deactivate?name=''&type=''
    public function deactivate(){
        // Assign the value of 'name' query parameter to the variable $name
        $name = $_GET['name'];

         // Assign the value of 'type' query parameter to the variable $type
        $type = $_GET['type'];

        try{
            if($type == 1){ // If $type is 1 (plugin)
                if(class_exists('Mods\\Plugins\\' . $name, false)){ // Check if the plugin class exists
                    $this->plugins[strtolower($name)]->down(); // Call the down() method of the plugin
        
                    // Delete the plugin from the database
                    $plugin = $this->pluginRepository->find(['name' => $name]);
                    $this->pluginRepository->delete($plugin);
        
                    $this->router->redirect('/plugin'); // Redirect to the plugin page
                }else{ // If the plugin class doesn't exist
                    $this->router->render('response', ['code' => 404], 404); // Render the 404 page
                }
            }elseif($type == 2){ // If $type is 2 (theme)
                if(class_exists('Mods\\Themes\\' . $name, false)){ // Check if the theme class exists
                    $this->themes[strtolower($name)]->down(); // Call the down() method of the theme
        
                    // Delete the theme from the database
                    $theme = $this->themeRepository->find(['name' => $name]);
                    $this->themeRepository->delete($theme);
                    
                    $this->router->redirect('/theme'); // Redirect to the theme page
                }else{ // If the theme class doesn't exist
                    $this->router->render('response', ['code' => 404], 404); // Render the 404 page
                }
    
            }else{ // If it's not a plugin or theme
                $this->router->render('response', ['code' => 400], 400);
            }
        }catch(Exception $e){
            if($type == 1){ // If $type is 1 (plugin)
                // Set the error message for the plugin page
                $_SESSION['error']['plugin'][] = 'Failed to unload the plugin: '. $name; 

                $this->router->redirect('/plugin'); // Redirect to the plugin page
            }elseif($type == 2){ // If $type is 2 (theme)
                // Set the error message for the theme page
                $_SESSION['error']['theme'][] = 'Failed to unload theme: '. $name;

                $this->router->redirect('/theme'); // Redirect to the theme page
            }else{ // If it's not a plugin or theme
                $this->router->render('response', ['code' => 400], 400);
            }
        }
        
    }

    public function uninstall()
    {
        // Assign the value of 'name' query parameter to the variable $name
        $name = $_GET['name'];

         // Assign the value of 'type' query parameter to the variable $type
        $type = $_GET['type'];

        if($type == 1){
            try {
                if(class_exists('Mods\\Plugins\\' . $name, false)){ // Check if the plugin class exists
                    $this->plugins[strtolower($name)]->down(); // Call the down() method of the plugin
                    $this->plugins[strtolower($name)]->uninstall(); // Call the uninstall() method of the plugin
        
                    // Delete the plugin from the database
                    $plugin = $this->pluginRepository->find(['name' => $name]);
                    if(isset($plugin)) $this->pluginRepository->delete($plugin);
                }
                
                $this->deleteDirectory('./mods/plugins/'. $name);
                $this->deleteDirectory('./public/mods/plugins/' . $name);

                // Redirect to the plugin page
                $this->router->redirect('/plugin');
            } catch (Exception $e) {
                // Set the error message for the plugin page
                $_SESSION['error']['plugin'][] = 'Failed to uninstall plugin: '. $name;
                // Redirect to the plugin page
                $this->router->redirect('/plugin');
            }
        }elseif($type == 2){
            try {
                if(class_exists('Mods\\Themes\\' . $name, false)){ // Check if the theme class exists
                    $this->themes[strtolower($name)]->down(); // Call the down() method of the theme
                    $this->themes[strtolower($name)]->uninstall(); // Call the uninstall() method of the theme
        
                    // Delete the theme from the database
                    $theme = $this->themeRepository->find(['name' => $name]);
                    if(isset($theme)) $this->themeRepository->delete($theme);
                }

                $this->deleteDirectory('./mods/themes/'. $name);
                $this->deleteDirectory('./public/mods/themes/' . $name);

                // Redirect to the theme page
                $this->router->redirect('/theme');
            } catch (Exception $e) {
                // Set the error message for the theme page
                $_SESSION['error']['theme'][] = 'Failed to uninstall theme: '. $name;
                // Redirect to the theme page
                $this->router->redirect('/theme');
            }
        }else{ // If it's not a plugin or theme
            $this->router->render('response', ['code' => 400], 400);
        }
    }

    // Check if given directory exists if so recursively deletes all the files and subdirectories inside it
    private function deleteDirectory(String $dir): void
    {
        try {
            if(file_exists($dir)){
                $di = new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS);
                $ri = new \RecursiveIteratorIterator($di, \RecursiveIteratorIterator::CHILD_FIRST);
                foreach ( $ri as $file ) {
                    $file->isDir() ? rmdir($file) : unlink($file);
                }
                rmdir($dir);
            }
        } catch (Exception $e) {
            throw new Exception('Cannot uninstall! ' . $e->getMessage());
        }
    }

    public function __destruct()
    {
        // Remove the 'style' element from the $_SESSION array
        unset($_SESSION['style']);

        // Remove the 'scripts' element from the $_SESSION array
        unset($_SESSION['scripts']);
        
    }
}

?>