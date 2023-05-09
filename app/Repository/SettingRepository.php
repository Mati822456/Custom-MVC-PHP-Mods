<?php

namespace App\Repository;

use App\Database;
use App\Models\Setting;

class SettingRepository extends Setting
{
    private Database $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    public function getAll(): array
    {
        $settings = [];

        foreach ($this->database->getAll('settings') as $result) {
            $setting = new Setting();
            $setting->setId($result['id']);
            $setting->setName($result['name']);
            $setting->setStatus($result['status']);

            $settings[] = $setting;
            unset($setting);
        }

        return $settings;
    }

    public function find(array $param): ?Setting
    {
        $result = $this->database->find('settings', $param);
        if ($result) {
            $setting = new Setting();
            $setting->setId($result['id']);
            $setting->setName($result['name']);
            $setting->setStatus($result['status']);

            return $setting;
        }

        return null;
    }

    public function store(Setting $setting)
    {
        $this->database->store(
            'settings',
            [
                'name'   => $setting->getName(),
                'status' => $setting->getStatus(),
            ]
        );
    }

    public function update(Setting $setting)
    {
        $this->database->update(
            'settings',
            ['status' => $setting->getStatus()],
            ['id'     => $setting->getId()]
        );
    }

    public function delete(Setting $setting)
    {
        $this->database->delete(
            'settings',
            ['name' => $setting->getName()]
        );
    }
}
