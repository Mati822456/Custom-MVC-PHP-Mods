<?php

namespace Mods\Themes;

use Mods\Themes\Theme;

class dark_mode implements Theme{

    public string $name;
    public string $description;
    public string $author;
    public string $version;
    public string $created;
    public int $type;
    public string $image;
    public string $supportedVersion;

    public function __construct(){
        $this->name = 'Dark_mode';
        $this->description = 'Simple theme with dark mode support.';
        $this->author = 'Mati822456';
        $this->version = '1.0.0.2';
        $this->created = '21.04.2023';
        $this->type = 2;
        $this->image = 'image.svg';
        $this->supportedVersion = '1.0.2.0';
    }

    public function run(){
        $_SESSION['style'][] = 'public\\mods\\Themes\\' . $this->name . '\\style.css';

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