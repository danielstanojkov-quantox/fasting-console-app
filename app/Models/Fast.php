<?php

namespace App\Models;

class Fast 
{
    public $all_fasts;
    public $active_fast;

    public function __construct()
    {
        $this->all_fasts = $this->getAllFasts();
        $this->active_fast = $this->setActiveFast();
    }

    protected function getAllFasts()
    {
        return json_decode(file_get_contents(APP_DB));
    }

    protected function setActiveFast()
    {
        $fast = array_filter($this->all_fasts, function($fast){
            return $fast->status == 'active';
        });

        return count($fast) > 0 ? $fast[0] : null;
    }

    public function isUserFasting()
    {
        return $this->active_fast ? true : false;
    }
}