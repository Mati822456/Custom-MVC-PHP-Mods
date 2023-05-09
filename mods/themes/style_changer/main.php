<?php

namespace Mods\Themes;

use Mods\Themes\Theme;

class style_changer implements Theme{

    public string $name;
    public string $description;
    public string $author;
    public string $version;
    public string $created;
    public int $type;
    public string $image;
    public string $supportedVersion;

    public function __construct(){
        $this->name = 'Style_changer';
        $this->description = 'Basic theme with additional column display functionality in plugins and themes views.';
        $this->author = 'Mati822456';
        $this->version = '1.0.1.4';
        $this->created = '21.04.2023';
        $this->type = 2;
        $this->image = 'image.svg';
        $this->supportedVersion = '1.0.5.0';
    }

    public function getDescription()
    {
        return 'Style_changer is a basic theme that adds additional functionality for displaying cards in the plugin and theme views. This theme only changes the style of how cards with mods are displayed, from square to oblong. Style_changer allows for better use of space and easier comparison of mods.';
    }

    public function run(){
        $_SESSION['style'][] = 'public\\mods\\themes\\' . $this->name . '\\style.css';

        if(!file_exists('./public/mods/themes/' . $this->name . '/style.css')){
            copy('./mods/themes/' . $this->name. '/style.css', './public/mods/themes/' . $this->name . '/style.css');
        }
    }
    public function down(){
        if(file_exists('./public/mods/themes/' . $this->name . '/style.css')){
            unlink('./public/mods/themes/' . $this->name . '/style.css');
        }
    }
    public function uninstall(){
        // In this case do nothing, Can be implemented e.g drop table, truncate table or delete records
        return NULL;
    }
}

?>