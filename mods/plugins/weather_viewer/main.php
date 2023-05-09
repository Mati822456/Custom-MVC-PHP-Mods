<?php

namespace Mods\Plugins;

class Weather_viewer implements Plugin
{
    public string $name;
    public string $description;
    public string $author;
    public string $version;
    public string $created;
    public int $type;
    public string $image;
    public string $supportedVersion;
    public array $requirements;

    public function __construct()
    {
        $this->name = 'Weather_viewer';
        $this->description = 'This plugin displays current weather in Warsaw on the main page.';
        $this->author = 'Mati822456';
        $this->version = '1.0.1.3';
        $this->created = '24.04.2023';
        $this->type = 1;
        $this->image = 'image.svg';
        $this->supportedVersion = '1.0.5.0';
        $this->requirements = ['Weather_core'];
    }

    public function getDescription()
    {
        return 'Weather_viewer is a plugin that displays the current weather in Warsaw on the homepage. This plugin allows users to quickly check the temperature, wind speed and weather condition information. Weather_viewer needs the Weather_core plugin to function, which provides weather data in JSON format. Weather_viewer displays a weather status card on the homepage, which is aesthetically pleasing and easy to read.';
    }

    public function run()
    {
        // Adds the script.js file path to the session scripts array
        $_SESSION['scripts'][] = 'public\\mods\\plugins\\'.$this->name.'\\script.js';

        // Check if the file exists in the given directory and if not copies from this directory
        if (!file_exists('./public/mods/plugins/'.$this->name.'/script.js')) {
            copy('./mods/plugins/'.$this->name.'/script.js', './public/mods/plugins/'.$this->name.'/script.js');
        }

        // Check if directory exists if not copy all files from directory /icons
        $dirToIconsDirectory = './public/mods/plugins/'.$this->name.'/icons';
        if (!file_exists($dirToIconsDirectory)) {
            mkdir($dirToIconsDirectory);
            copy(__DIR__.'\icons\clear.svg', $dirToIconsDirectory.'/clear.svg');
            copy(__DIR__.'\icons\mainly_clear.svg', $dirToIconsDirectory.'/mainly_clear.svg');
            copy(__DIR__.'\icons\overcast.svg', $dirToIconsDirectory.'/overcast.svg');
            copy(__DIR__.'\icons\partly_cloudy.svg', $dirToIconsDirectory.'/partly_cloudy.svg');
            copy(__DIR__.'\icons\rain.svg', $dirToIconsDirectory.'/rain.svg');
            copy(__DIR__.'\icons\snow.svg', $dirToIconsDirectory.'/snow.svg');
            copy(__DIR__.'\icons\thunderstorm.svg', $dirToIconsDirectory.'/thunderstorm.svg');
            copy(__DIR__.'\icons\moon.svg', $dirToIconsDirectory.'/moon.svg');
        }
    }

    public function uninstall()
    {
        // In this case do nothing, Can be implemented e.g drop table, truncate table or delete records
        return null;
    }

    public function down()
    {
        // Check if the file exists in the given directory and if so deletes it
        if (file_exists('./public/mods/plugins/'.$this->name.'/script.js')) {
            unlink('./public/mods/plugins/'.$this->name.'/script.js');
        }

        // Check if directory exists if so delete all files from /icons and delete directory
        $dirToIconsDirectory = './public/mods/plugins/'.$this->name.'/icons';
        if (file_exists($dirToIconsDirectory)) {
            unlink($dirToIconsDirectory.'/clear.svg');
            unlink($dirToIconsDirectory.'/mainly_clear.svg');
            unlink($dirToIconsDirectory.'/overcast.svg');
            unlink($dirToIconsDirectory.'/partly_cloudy.svg');
            unlink($dirToIconsDirectory.'/rain.svg');
            unlink($dirToIconsDirectory.'/snow.svg');
            unlink($dirToIconsDirectory.'/thunderstorm.svg');
            unlink($dirToIconsDirectory.'/moon.svg');
            rmdir($dirToIconsDirectory);
        }
    }
}
