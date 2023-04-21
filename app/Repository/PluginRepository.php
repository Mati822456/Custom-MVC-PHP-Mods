<?php

namespace App\Repository;

use App\Database;
use App\Models\Plugin;

class PluginRepository extends Plugin{

    private Database $database;
    private Array $plugins;

    public function __construct()
    {
        $this->database = new Database;

        $this->plugins = [];

        foreach($this->database->getAll('plugins') as $result){
            $plugin = new Plugin;
            $plugin->setId($result['id']);
            $plugin->setName($result['name']);

            $this->plugins[] = $plugin;
            unset($plugin);
        }
    }

    public function getAll(): Array
    {
        return $this->plugins;
    }

    public function find(Array $param): ?Plugin
    {
        $result = $this->database->find('plugins', $param);
        if($result){
            $plugin = new Plugin;
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

    public function update(Plugin $plugin): Plugin
    {
        $this->database->update(
            'plugins',
            ['name = "' . $plugin->getName() . '"'],
            ['id = '. $plugin->getId()]
        );

        return $plugin;
    }

    public function delete(Plugin $plugin)
    {
        $this->database->delete(
            'plugins',
            ['name' => $plugin->getName()]
        );
    }
}