<?php

namespace App\Controllers;

use App\Repository\SettingRepository;

class SettingsController extends Controller
{
    private $settingRepository;

    public function __construct()
    {
        parent::__construct();
        $this->settingRepository = new SettingRepository();
    }

    public function index()
    {
        // Render the "settings" view, passing the available settings,
        $this->router->render('settings', [
            'settings' => $this->manager->getSettings(),
        ]);
    }

    public function update()
    {
        // Check if $_POST is empty, if so redirect to response page with code 400
        if (empty($_POST)) {
            $this->router->render('response', ['code' => 400], 400);
        }

        // Initialize empty array
        $data = [];

        // Check for checked options
        if (!empty($_POST['alerts-error'])) {
            $data['alerts-error'] = 1;
        } else {
            $data['alerts-error'] = 0;
        }

        if (!empty($_POST['alerts-warning'])) {
            $data['alerts-warning'] = 1;
        } else {
            $data['alerts-warning'] = 0;
        }

        if (!empty($_POST['alerts-info'])) {
            $data['alerts-info'] = 1;
        } else {
            $data['alerts-info'] = 0;
        }

        if (!empty($_POST['alerts-success'])) {
            $data['alerts-success'] = 1;
        } else {
            $data['alerts-success'] = 0;
        }

        // Loop through setting to update
        foreach ($data as $key => $status) {
            // Search for the setting in the database and receive the model
            $setting = $this->settingRepository->find(['name' => $key]);
            if ($setting) { // If setting exists
                $setting->setStatus($status);
                $this->settingRepository->update($setting);
            }
        }

        // Refresh Settings
        $this->manager->refreshSettings();

        // Create success alert
        $this->manager->createAlert('setting', 'success', 'Settings successfully saved!');

        // Route back
        $this->router->back();
    }
}
