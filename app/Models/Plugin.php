<?php

namespace App\Models;

class Plugin{

    private Int $id;
    private String $name;

    protected function setId(Int $id)
    {
        $this->id = $id;
    }

    public function getId(): Int
    {
        return $this->id;
    }
    
    public function getName(): String
    {
        return $this->name;
    }

    public function setName(String $name)
    {
        $this->name = $name;
    }

}