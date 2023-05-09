<?php

namespace App\Repository;

use App\Database;
use App\Models\Plugin;

class PluginRepository extends Plugin
{
    private Database $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    public function getAll(): array
    {
        $plugins = [];

        foreach ($this->database->getAll('plugins') as $result) {
            $plugin = new Plugin();
            $plugin->setId($result['id']);
            $plugin->setName($result['name']);

            $plugins[] = $plugin;
            unset($plugin);
        }

        return $plugins;
    }

    public function find(array $param): ?Plugin
    {
        $result = $this->database->find('plugins', $param);
        if ($result) {
            $plugin = new Plugin();
            $plugin->setId($result['id']);
            $plugin->setName($result['name']);

            return $plugin;
        }

        return null;
    }

    public function store(Plugin $plugin)
    {
        $this->database->store(
            'plugins',
            ['name' => $plugin->getName()]
        );
    }

    public function update(Plugin $plugin)
    {
        $this->database->update(
            'plugins',
            ['name' => $plugin->getName()],
            ['id'   => $plugin->getId()]
        );
    }

    public function delete(Plugin $plugin)
    {
        $this->database->delete(
            'plugins',
            ['name' => $plugin->getName()]
        );
    }
}
