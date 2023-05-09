<?php

namespace Mods\Plugins;

use Mods\Plugins\Plugin;

class database_viewer implements Plugin{

    public string $name;
    public string $description;
    public string $author;
    public string $version;
    public string $created;
    public int $type;
    public string $image;
    public string $supportedVersion;

    public function __construct(){
        $this->name = 'Database_viewer';
        $this->description = 'Database_viewer is a straightforward plugin enabling users to browse database.';
        $this->author = 'Mati822456';
        $this->version = '1.0.1.4';
        $this->created = '21.04.2023';
        $this->type = 1;
        $this->image = 'image.svg';
        $this->supportedVersion = '1.0.5.0';
    }

    public function getDescription()
    {
        return 'Database_viewer is a simple plugin that allows users to view the database. The plugin allows you to display a list of active plugins and themes. The plugin also adds an additional tab on the home page that allows users to quickly navigate to the database viewer. In addition, the plugin creates an additional /database location that displays the entire contents of the database in tabular form. The plugin also adds an additional tab in the navigation that makes it easy to navigate to the database overview.';
    }

    public function run(){
        // Adds the script.js file path to the session scripts array
        $_SESSION['scripts'][] = 'public\\mods\\plugins\\' . $this->name . '\\script.js';

        // Adds the GET method database route to the session routes array with the associated controller and function.
        $_SESSION['routes']['/database'] = ['method' => 'GET', 'controller' => 'DatabaseController', 'function' => 'index', 'variables' => NULL];
        
        // Checks if the DatabaseController.php file exists in the Controller directory, 
        // and if not, copies it from the plugin directory.
        if(!file_exists('./App/Controllers/DatabaseController.php')){
            copy(__DIR__ . '/DatabaseController.php', './App/Controllers/DatabaseController.php');
        }
        
        // Checks if the index.php file exists in the database_viewer directory inside the Views directory,
        // and if not, creates the directory and copies the file from the plugin directory.
        if(!file_exists('./Views/database_viewer/index.php')){
            mkdir('./Views/database_viewer');
            copy(__DIR__ . '/index.php', './Views/database_viewer/index.php');
        }

        if(!file_exists('./public/mods/plugins/' . $this->name . '/script.js')){
            copy('./mods/plugins/' . $this->name. '/script.js', './public/mods/plugins/' . $this->name . '/script.js');
        }
    }
    public function uninstall(){
        // In this case do nothing, Can be implemented e.g drop table, truncate table or delete records
        return NULL;
    }
    public function down(){
        // Removes the '/database' route from the session variable 'routes'
        unset($_SESSION['routes']['/database']);

        // Checks if the 'DatabaseController.php' file exists in the 'Controller' directory and deletes it if it does.
        if(file_exists('./App/Controllers/DatabaseController.php')){
            unlink('./App/Controllers/DatabaseController.php');
        }

        // Checks if the 'index.php' file exists in the 'views/database_viewer' directory, deletes it, and then removes the 'database_viewer' directory if it is empty.
        if(file_exists('./Views/database_viewer/index.php')){
            unlink('./Views/database_viewer/index.php');
            rmdir('./Views/database_viewer');
        }

        // Check if the file exists in the given directory and if so deletes it
        if(file_exists('./public/mods/plugins/' . $this->name . '/script.js')){
            unlink('./public/mods/plugins/' . $this->name . '/script.js');
        }
    }
}
?>