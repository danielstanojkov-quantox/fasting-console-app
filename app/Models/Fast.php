<?php

namespace App\Models;

use App\Components\Fast as FastInstance;
use Carbon\Carbon;

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
        $fasts = array_map(function ($fast) {
            return new FastInstance($fast);
        }, $fasts);

        $this->all_fasts = $fasts;
    }

    protected function setActiveFast()
    {
        $fast = array_filter($this->all_fasts, function ($fast) {
            return $fast->status == 'active';
        });

     
        $this->active_fast = count($fast) > 0 ? array_pop($fast) : null;
    }

    public function isUserFasting()
    {
        return $this->active_fast ? true : false;
    }

    public function saveActiveFast($start_date, $fast_type)
    {
        $end_date = Carbon::parse($start_date)->addHours($fast_type);

        $fast = [
            'id' => time(),
            'status' => 'active',
            'type' => (int)$fast_type,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        $fasts = json_decode(file_get_contents(APP_DB));
        array_push($fasts, $fast);

        file_put_contents(APP_DB, json_encode($fasts));

        $this->loadFasts();
        $this->setActiveFast();
    }

    public function endActiveFast()
    {
       $fasts = file_get_contents(APP_DB);
       $fasts = str_replace('"active"', '"inactive"', $fasts);
       file_put_contents(APP_DB, $fasts);

       $this->loadFasts();
       $this->setActiveFast();
    }
}
