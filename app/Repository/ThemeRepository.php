<?php

namespace App\Repository;

use App\Database;
use App\Models\Theme;

class ThemeRepository extends Theme
{
    private Database $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    public function getAll(): array
    {
        $themes = [];

        foreach ($this->database->getAll('themes') as $result) {
            $theme = new Theme();
            $theme->setId($result['id']);
            $theme->setName($result['name']);

            $themes[] = $theme;
            unset($theme);
        }

        return $themes;
    }

    public function find(array $param): ?Theme
    {
        $result = $this->database->find('themes', $param);
        if ($result) {
            $theme = new Theme();
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

    public function update(Theme $theme)
    {
        $this->database->update(
            'themes',
            ['name' => $theme->getName()],
            ['id'   => $theme->getId()]
        );
    }

    public function delete(Theme $theme)
    {
        $this->database->delete(
            'themes',
            ['name' => $theme->getName()]
        );
    }
}
