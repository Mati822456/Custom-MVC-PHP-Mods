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
    protected array $modsCannotRun;
    protected array $requiredMods;
    protected array $incompatibleMods;

    protected array $Plugins_names;
    protected array $Themes_names;

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
        $this->modsCannotRun = [];
        $this->requiredMods = [];
        $this->incompatibleMods = [];

        $this->Plugins_names = [];
        $this->Themes_names = [];
        
        // Create a new instance of the Database class
        $this->database = new Database;

        // Create a new instance of the Router class
        $this->router = new Router();

        // Create a new instances of the Mods classes
        $this->pluginRepository = new PluginRepository;
        $this->themeRepository = new ThemeRepository;

        // Get all the activated plugins and themes from the database
        $activatedPlugins = $this->pluginRepository->getAll();
        $activatedThemes = $this->themeRepository->getAll();
        
        // Get all the names of the activated plugins and themes.
        foreach($activatedPlugins as $active){
            $this->Plugins_names[] = strtolower($active->getName());
        }
        foreach($activatedThemes as $active){
            $this->Themes_names[] = strtolower($active->getName());
        }

        // Loads the plugins and themes
        $this->load();
       
    }

    private function load()
    {
        // Initialize empty arrays
        $_SESSION['style'] = [];
        $_SESSION['scripts'] = [];
        $arrayOfModsNames = [];

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
                    $this->createAlert('plugin', 'warning', '"'. $file . '" may not work properly. Supported version: '. $this->plugins[$file]->supportedVersion);
                }

                // Check if the plugin has the requirements
                if(isset($this->plugins[$file]->requirements)){
                    $this->requiredMods[$this->plugins[$file]->name] = $this->plugins[$file]->requirements;
                }

                // Check if the plugins has the incompatible mods
                if(isset($this->plugins[$file]->incompatible)){
                    $this->incompatibleMods[$this->plugins[$file]->name] = $this->plugins[$file]->incompatible;
                }

            }catch(Throwable $e){
                // Sets an error message in the $_SESSION array if a plugin fails to load
                $this->createAlert('plugin', 'error', 'Error while loading "' . $file . '" : ' . $e->getMessage());
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
                    $this->createAlert('theme', 'warning', '"'. $file . '" may not work properly. Supported version: '. $this->themes[$file]->supportedVersion);
                }

                // Check if the theme has the requirements
                if(isset($this->themes[$file]->requirements)){
                    $this->requiredMods[$this->themes[$file]->name] = $this->themes[$file]->requirements;
                }

                // Check if the theme has the incompatible mods
                if(isset($this->themes[$file]->incompatible)){
                    $this->incompatibleMods[$this->themes[$file]->name] = $this->themes[$file]->incompatible;
                }

            }catch(Throwable $e){
                // Sets an error message in the $_SESSION array if a theme fails to load
                $this->createAlert('theme', 'error', 'Error while loading "' . $file . '" : ' . $e->getMessage());
            }
        }
        
        // Merge the array of mods names with the keys of the plugins array.
        $arrayOfModsNames = array_merge($arrayOfModsNames, array_keys($this->plugins));
        // Merge the array of mods names with the keys of the themes array.
        $arrayOfModsNames = array_merge($arrayOfModsNames, array_keys($this->themes));
        
        // Initializing variables for counting.
        $totalRequiredMods = 0;
        $counter = 0;

        // String of mods names that doesn't exists
        $namesOfNotExistMods = '';
        
        // Loop through the array of required mods with keys as the name of the current mod (that needs other mods) and values as an array of required mods.
        foreach($this->requiredMods as $key => $value){
            // Set the total number of required mods for the current mod.
            $totalRequiredMods = count($this->requiredMods[$key]);

            // Loop through required mods for the current mod 
            foreach($value as $mod){
                // Check if required mod exists
                if(in_array(strtolower($mod), $arrayOfModsNames, true)){
                    $counter++;
                }else{
                    $namesOfNotExistMods .= $mod . ', ';
                }
            }

            // If not all required mods exist, the plugin or theme is being deleted from database if exists
            if($totalRequiredMods != $counter){
                if(in_array(strtolower($key), $this->Plugins_names)){
                    $plugin = $this->pluginRepository->find(['name' => $key]);
                    $this->pluginRepository->delete($plugin);
                    unset($this->Plugins_names[array_search(strtolower($mod), $this->Plugins_names)]);
                }elseif(in_array(strtolower($key), $this->Themes_names)){
                    $theme = $this->themeRepository->find(['name' => $key]);
                    $this->themeRepository->delete($theme);
                    unset($this->Themes_names[array_search(strtolower($mod), $this->Themes_names)]);
                }
            }
            
            // Check if not all required mods exist and if not generate error message
            if(!empty($namesOfNotExistMods)){
                $namesOfNotExistMods = rtrim($namesOfNotExistMods, ', ');
                $this->modsCannotRun[] = $key;
                if(in_array(strtolower($key), array_keys($this->plugins))){
                    $this->createAlert('plugin', 'error', 'Not all mods exist to run "'. $key .'" plugin, required: ' . $namesOfNotExistMods);
                }elseif(in_array(strtolower($key), array_keys($this->themes))){
                    $this->createAlert('theme', 'error', 'Not all mods exist to run "'. $key .'" theme, required: ' . $namesOfNotExistMods);
                }
            }

            // Reset variables for the next mod in the loop
            $counter = 0;
            $namesOfNotExistMods = '';
        }

        // String of launched incompatible mods
        $namesOfLaunchedIncompatible = '';

        // Loop through each incompatible mod and check if it's both enabled and incompatible with the current mod
        foreach($this->incompatibleMods as $key => $value){
            foreach($value as $mod){
                // Check if the current incompatible mod is enabled and incompatible with the current mod
                if((in_array(strtolower($mod), $this->Plugins_names) || in_array(strtolower($mod), $this->Themes_names)) && (in_array(strtolower($key), $this->Plugins_names) || in_array(strtolower($key), $this->Themes_names))){
                    $namesOfLaunchedIncompatible .= $mod . ', ';

                    // Check if the current incompatible mod is activated and is a plugin and delete it if it is
                    if(in_array(strtolower($mod), $this->Plugins_names)){
                        $plugin = $this->pluginRepository->find(['name' => $mod]);
                        $this->pluginRepository->delete($plugin);
                        unset($this->Plugins_names[array_search(strtolower($mod), $this->Plugins_names)]);
                    }

                    // Check if the current incompatible mod is activated and is a theme and delete it if it is
                    if(in_array(strtolower($mod), $this->Themes_names)){
                        $theme = $this->themeRepository->find(['name' => $mod]);
                        $this->themeRepository->delete($theme);
                        unset($this->Themes_names[array_search(strtolower($mod), $this->Themes_names)]);
                    }
                }
            }

            // If there was any launched incompatible mods, create a warning alert for both plugins and themes.
            if(!empty($namesOfLaunchedIncompatible)){
                $namesOfLaunchedIncompatible = rtrim($namesOfLaunchedIncompatible, ', ');

                $this->createAlert('plugin', 'warning', 'Disabling incompatible mods: '. $namesOfLaunchedIncompatible);
                $this->createAlert('theme', 'warning', 'Disabling incompatible mods: '. $namesOfLaunchedIncompatible);
            }

            $namesOfLaunchedIncompatible = '';
        }
        
        // Loop through each mod name in the given array and run the mod if it's activated
        foreach($arrayOfModsNames as $mod){
            if(in_array(strtolower($mod), $this->Plugins_names)){
                $this->plugins[strtolower($mod)]->run();
            }
            if(in_array(strtolower($mod), $this->Themes_names)){
                $this->themes[strtolower($mod)]->run();
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

    // Returns an array of mods that cannot be run
    public function getModsCannotRun(): Array
    {
        return $this->modsCannotRun;
    }

    // Activate plugin or theme using url: /activate?name=''&type=''
    public function activate(){
        // Assign the value of 'name' query parameter to the variable $name
        $name = $_GET['name'];

        // Assign the value of 'type' query parameter to the variable $type
        $type = $_GET['type'];
       
        // Check if name of the mod is in array of mods that cannot be run
        if(in_array($_GET['name'], $this->modsCannotRun)){
            $this->router->render('response', ['code' => 400], 400);
        }

        // Check if this mod is active, if so redirect to the appropriate page
        if(in_array(strtolower($name), $this->Plugins_names)){
            $this->router->redirect('/plugin'); // Redirect to the plugin page
        }
        if(in_array(strtolower($name), $this->Themes_names)){
            $this->router->redirect('/theme'); // Redirect to the theme page
        }

        // String of names of mods that have not been run
        $namesOfNotEnabledMod = '';
        
        // Check if the mod name exists in the array of required mods
        if(isset($this->requiredMods[$name])){
            // Loop through the required mods of given mod name
            foreach($this->requiredMods[$name] as $mod){
                // Check if the mod is not enabled in the plugins array or in the themes array
                if((!in_array(strtolower($mod), $this->Plugins_names)) && (!in_array(strtolower($mod), $this->Themes_names))){
                    $namesOfNotEnabledMod .= $mod . ', ';
                }
            }
            // If there are mods that are not enabled, set an error message and redirect to the appropriate page
            if(!empty($namesOfNotEnabledMod)){
                $namesOfNotEnabledMod = rtrim($namesOfNotEnabledMod, ', ');
                if($type == 1){
                    $this->createAlert('plugin', 'error', 'Not all mods are running for "'. $name .'" plugin, required: ' . $namesOfNotEnabledMod);
                    $this->router->redirect('/plugin'); // Redirect to the plugin page
                }elseif($type == 2){
                    $this->createAlert('theme', 'error', 'Not all mods are running for "'. $name .'" theme, required: ' . $namesOfNotEnabledMod);
                    $this->router->redirect('/theme'); // Redirect to the theme page
                }
            }
        }

        // String of launched incompatible mods
        $namesOfLaunchedIncompatible = '';

        // String of mods that cannot have incompatible mods
        $modsThatCannotHaveIncompatible = '';

        // Check if there any incompatible mods
        if(isset($this->incompatibleMods)){
            // Loop through incompatible mods to check if they are enabled
            foreach($this->incompatibleMods as $key => $value){
                foreach($value as $mod){
                    // Check if the incompatible mod is already enabled
                    if($key == $name){
                        if((in_array(strtolower($mod), $this->Plugins_names)) || (in_array(strtolower($mod), $this->Themes_names))){
                            $namesOfLaunchedIncompatible .= $mod . ', ';
                        }
                    }else{
                        // Check if the current mod will cause problems with other enabled mods
                        if($name === $mod){
                            if(in_array(strtolower($key), $this->Plugins_names) || in_array(strtolower($key), $this->Themes_names)){
                                $modsThatCannotHaveIncompatible .= $key . ', ';
                            }
                        }
                    }
                }
            }
        }

        // Check if there are any incompatible mods enabled and creating an error alert if true
        if(!empty($namesOfLaunchedIncompatible)){
            $namesOfLaunchedIncompatible = rtrim($namesOfLaunchedIncompatible, ', ');
            if($type == 1){
                $this->createAlert('plugin', 'error', 'Incompatible mods are enabled: '. $namesOfLaunchedIncompatible);
                $this->router->redirect('/plugin');
            }elseif($type == 2){
                $this->createAlert('theme', 'error', 'Incompatible mods are enabled: '. $namesOfLaunchedIncompatible);
                $this->router->redirect('/theme');
            }
        }

        // Check if the current mod will cause problems with other enabled mods and creating an error alert if true
        if(!empty($modsThatCannotHaveIncompatible)){
            $modsThatCannotHaveIncompatible = rtrim($modsThatCannotHaveIncompatible, ', ');
            if($type == 1){
                $this->createAlert('plugin', 'error', 'This mod will cause problems in:  '. $modsThatCannotHaveIncompatible);
                $this->router->redirect('/plugin');
            }elseif($type == 2){
                $this->createAlert('theme', 'error', 'This mod will cause problems in: '. $modsThatCannotHaveIncompatible);
                $this->router->redirect('/theme');
            }
        }
        

        try{
            if($type == 1){ // If variable $type is equal to 1 (plugin)
                if(class_exists('Mods\\Plugins\\' . $name, false)){ // Check if the plugin class exists
                    $this->plugins[strtolower($name)]->run(); // Run the plugin's main method
                    
                    // Store the plugin name in the database
                    $plugin = new Plugin;
                    $plugin->setName($name);
                    $this->pluginRepository->store($plugin);
                    
                    $this->createAlert('plugin', 'success', $name . ' has been successfully launched');

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

                    $this->createAlert('theme', 'success', $name . ' has been successfully launched');
                    
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
                $this->createAlert('plugin', 'error', 'Failed to load plugin: '. $name);
                // Redirect to the plugin page
                $this->router->redirect('/plugin');
            }elseif($type == 2){ // If $type is equal to 2 (theme)
                // Set the error message for the theme page
                $this->createAlert('theme', 'error', 'Failed to load theme: '. $name);
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
        
        // Check if this mod is active, if not then redirect to the appropriate page
        if(!in_array(strtolower($name), $this->Plugins_names) && !in_array(strtolower($name), $this->Themes_names)){
            if($type == 1){
                $this->router->redirect('/plugin'); // Redirect to the plugin page
            }elseif($type == 2){
                $this->router->redirect('/theme'); // Redirect to the theme page
            }
        }

        // String of mod names that use the given mod name
        $arrayOfModsThatUses = '';

        // Check if any mods are required
        if(isset($this->requiredMods)){
            // Loop through the required mods
            foreach($this->requiredMods as $key => $mods){
                // Check if given mod name exists in array of required mods
                if(in_array($name, $mods)){
                    // Check if any mods that uses this given mod is running
                    if(in_array(strtolower($key), $this->Plugins_names) || in_array(strtolower($key), $this->Themes_names)){
                        $arrayOfModsThatUses .= $key . ', ';
                    }
                }
            }

            // Check if there are any mods that use given mod
            if(!empty($arrayOfModsThatUses)){
                $arrayOfModsThatUses = rtrim($arrayOfModsThatUses, ', ');
                if($type == 1){
                    $this->createAlert('plugin', 'error', 'Disabling this plugin is not allowed! Used by: ' . $arrayOfModsThatUses);
                    $this->router->redirect('/plugin'); // Redirect to the plugin page
                }elseif($type == 2){
                    $this->createAlert('theme', 'error', 'Disabling this theme is not allowed! Used by: ' . $arrayOfModsThatUses);
                    $this->router->redirect('/theme'); // Redirect to the theme page
                }
            }
        }


        try{
            if($type == 1){ // If $type is 1 (plugin)
                if(class_exists('Mods\\Plugins\\' . $name, false)){ // Check if the plugin class exists
                    $this->plugins[strtolower($name)]->down(); // Call the down() method of the plugin
        
                    // Delete the plugin from the database
                    $plugin = $this->pluginRepository->find(['name' => $name]);
                    $this->pluginRepository->delete($plugin);

                    $this->createAlert('plugin', 'success', $name . ' has been successfully deactivated');
        
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
                    
                    $this->createAlert('theme', 'success', $name . ' has been successfully deactivated');

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
                $this->createAlert('plugin', 'error', 'Failed to unload the plugin: '. $name);

                $this->router->redirect('/plugin'); // Redirect to the plugin page
            }elseif($type == 2){ // If $type is 2 (theme)
                // Set the error message for the theme page
                $this->createAlert('theme', 'error', 'Failed to unload theme: '. $name);

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

                $this->createAlert('plugin', 'success', $name . ' has been successfully uninstalled');

                // Redirect to the plugin page
                $this->router->redirect('/plugin');
            } catch (Exception $e) {
                // Set the error message for the plugin page
                $this->createAlert('plugin', 'error', 'Failed to uninstall plugin: '. $name);
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

                $this->createAlert('theme', 'success', $name . ' has been successfully uninstalled');

                // Redirect to the theme page
                $this->router->redirect('/theme');
            } catch (Exception $e) {
                // Set the error message for the theme page
                $this->createAlert('theme', 'error', 'Failed to uninstall theme: '. $name);
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

    // Create alert message in the $_SESSION array
    public function createAlert(String $modelType, String $alertType, String $description)
    {
        // Check if alert message exists with provided parameters
        if(isset($_SESSION['message'][$modelType][$alertType])){
            if(!in_array($description, $_SESSION['message'][$modelType][$alertType])){
                $_SESSION['message'][$modelType][$alertType][] = $description;
            }
        }else{
            $_SESSION['message'][$modelType][$alertType][] = $description;
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