<?php

namespace Mods\Themes;

use Mods\Themes\Theme;

class card_beautify implements Theme{

    public string $name;
    public string $description;
    public string $author;
    public string $version;
    public string $created;
    public int $type;
    public string $image;
    public string $supportedVersion;
    public array $incompatible;

    public function __construct(){
        $this->name = 'Card_beautify';
        $this->description = 'This theme enhances the visual appeal of cards by making them square-shaped.';
        $this->author = 'Mati822456';
        $this->version = '1.0.0.0';
        $this->created = '29.04.2023';
        $this->type = 2;
        $this->image = 'image.svg';
        $this->supportedVersion = '1.0.3.0';
        $this->incompatible = ['Style_changer'];
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