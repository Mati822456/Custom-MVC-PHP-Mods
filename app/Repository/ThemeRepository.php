<?php

namespace App\Repository;

use App\Database;
use App\Models\Theme;

class ThemeRepository extends Theme{

    private Database $database;
    private Array $themes;

    public function __construct()
    {
        $this->database = new Database;

        $this->themes = [];

        foreach($this->database->getAll('themes') as $result){
            $theme = new Theme;
            $theme->setId($result['id']);
            $theme->setName($result['name']);

            $this->themes[] = $theme;
            unset($theme);
        }
    }

    public function getAll(): Array
    {
        return $this->themes;
    }

    public function find(Array $param): ?Theme
    {
        $result = $this->database->find('themes', $param);
        if($result){
            $theme = new Theme;
            $theme->setId($result['id']);
            $theme->setName($result['name']);
            return $theme;
        }
        return null;
    }

    public function store(Theme $theme)
    {
        $this->database->store(
            'themes',
            ['name' => $theme->getName()]
        );
    }

    public function update(Theme $theme): Theme
    {
        $this->database->update(
            'themes',
            ['name = "' . $theme->getName() . '"'],
            ['id = '. $theme->getId()]
        );

        return $theme;
    }

    public function delete(Theme $theme)
    {
        $this->database->delete(
            'themes',
            ['name' => $theme->getName()]
        );
    }
}