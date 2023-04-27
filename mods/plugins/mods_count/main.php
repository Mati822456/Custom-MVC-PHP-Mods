<?php

namespace Mods\Plugins;

use Mods\Plugins\Plugin;

class Mods_count implements Plugin{

    public string $name;
    public string $description;
    public string $author;
    public string $version;
    public string $created;
    public int $type;
    public string $image;
    public string $supportedVersion;

    public function __construct(){
        $this->name = 'Mods_count';
        $this->description = 'This plugin displays the number of mods (on the main page) that are activated.';
        $this->author = 'Mati822456';
        $this->version = '1.0.0.2';
        $this->created = '21.04.2023';
        $this->type = 1;
        $this->image = 'image.svg';
        $this->supportedVersion = '1.0.2.0';
    }

    public function run(){
        // Adds the script.js file path to the session scripts array
        $_SESSION['scripts'][] = 'public\\mods\\plugins\\' . $this->name . '\\script.js';

        // Adds the GET method mods-list route to the session routes array with the associated controller and function.
        $_SESSION['routes']['/mods-list'] = ['method' => 'GET', 'controller' => 'ModsCountController', 'function' => 'index', 'variables' => NULL];

        // Checks if the ModsCountController.php file exists in the Controller directory, 
        // and if not, copies it from the plugin directory.
        if(!file_exists('./App/Controllers/ModsCountController.php')){
            copy(__DIR__ . '/ModsCountController.php', './App/Controllers/ModsCountController.php');
        }

        // Check if the file exists in the given directory and if not copies from this directory
        if(!file_exists('./public/mods/plugins/' . $this->name . '/script.js')){
            copy('./mods/plugins/' . $this->name. '/script.js', './public/mods/plugins/' . $this->name . '/script.js');
        }
    }
    public function uninstall(){
        // In this case do nothing, Can be implemented e.g drop table, truncate table or delete records
        return NULL;
    }
    public function down(){
        // Removes the '/mods-list' route from the session variable 'routes'
        unset($_SESSION['routes']['/mods-list']);

        // Checks if the 'ModsCountController.php' file exists in the 'Controller' directory and deletes it if it does.
        if(file_exists('./App/Controllers/ModsCountController.php')){
            unlink('./App/Controllers/ModsCountController.php');
        }

        // Check if the file exists in the given directory and if so deletes it
        if(file_exists('./public/mods/plugins/' . $this->name . '/script.js')){
            unlink('./public/mods/plugins/' . $this->name . '/script.js');
        }
    }
}
?>