<?php

namespace App\Models;

use App\Components\Fast as FastInstance;

class Fast 
{
    public $all_fasts;
    public $active_fast;

    public function __construct()
    {
        $this->loadFasts();
        $this->setActiveFast();
    }

    protected function loadFasts()
    {
        $fasts = json_decode(file_get_contents(APP_DB));
        $fasts = array_map(function($fast){
            return new FastInstance($fast);
        }, $fasts);

        $this->all_fasts = $fasts;
    }

    protected function setActiveFast()
    {
        $fast = array_filter($this->all_fasts, function($fast){
            return $fast->status == 'active';
        });

        $this->active_fast = count($fast) > 0 ? $fast[0] : null;
    }

    public function isUserFasting()
    {
        return $this->active_fast ? true : false;
    }
}