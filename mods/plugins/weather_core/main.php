<?php

namespace Mods\Plugins;

use Mods\Plugins\Plugin;

class Weather_core implements Plugin{

    public string $name;
    public string $description;
    public string $author;
    public string $version;
    public string $created;
    public int $type;
    public string $image;
    public string $supportedVersion;

    public function __construct(){
        $this->name = 'Weather_core';
        $this->description = 'The basic core used to download weather data from Warsaw, which can be used in other plugins.';
        $this->author = 'Mati822456';
        $this->version = '1.0.1.3';
        $this->created = '24.04.2023';
        $this->type = 1;
        $this->image = 'image.svg';
        $this->supportedVersion = '1.0.5.0';
    }

    public function getDescription()
    {
        return 'Weather_core is a basic plugin that is used to download weather data from Warsaw at the current hour. This plugin allows other plugins to use the current temperature, wind speed and weather condition information. Weather_core adds a /get-weather location that returns data in JSON format that can be easily processed by other plugins. Weather_core is easy to use and provides a stable and reliable source of weather data.';
    }

    public function run(){

        // Adds the GET method get-weather route to the session routes array with the associated controller and function.
        $_SESSION['routes']['/get-weather'] = ['method' => 'GET', 'controller' => 'WeatherCoreController', 'function' => 'index', 'variables' => NULL];

        // Checks if the WeatherCoreController.php file exists in the Controller directory, 
        // and if not, copies it from the plugin directory.
        if(!file_exists('./App/Controllers/WeatherCoreController.php')){
            copy(__DIR__ . '/WeatherCoreController.php', './App/Controllers/WeatherCoreController.php');
        }
    }
    public function uninstall(){
        // In this case do nothing, Can be implemented e.g drop table, truncate table or delete records
        return NULL;
    }
    public function down(){
        // Removes the '/get-weather' route from the session variable 'routes'
        unset($_SESSION['routes']['/get-weather']);

        // Checks if the 'WeatherCoreController.php' file exists in the 'Controller' directory and deletes it if it does.
        if(file_exists('./App/Controllers/WeatherCoreController.php')){
            unlink('./App/Controllers/WeatherCoreController.php');
        }
    }
}
?>