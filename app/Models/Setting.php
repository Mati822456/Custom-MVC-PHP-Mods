<?php

namespace App\Models;

class Setting{

    private Int $id;
    private String $name;
    private Int $status;

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

    public function getStatus(): Int
    {
        return $this->status;
    }

    public function setStatus(Int $status)
    {
        $this->status = $status;
    }

}