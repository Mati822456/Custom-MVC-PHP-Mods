<?php

namespace Mods\Plugins;

interface Plugin{

    public function run();
    public function down();
    public function uninstall();
    public function getDescription();
    
}

?>