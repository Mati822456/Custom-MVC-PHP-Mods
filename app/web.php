<?php

namespace App;

use App\Router;

$route = new Router;

$route->get('/', 'MainController', 'index');
$route->get('/plugin', 'PluginController', 'index');
$route->get('/theme', 'ThemeController', 'index');
$route->get('/activate', 'Manager', 'activate');
$route->get('/deactivate', 'Manager', 'deactivate');
$route->get('/uninstall', 'Manager', 'uninstall');

$route->get('/show', 'ShowController', 'show');

$route->run();