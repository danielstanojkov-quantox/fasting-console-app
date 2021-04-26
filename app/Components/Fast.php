<?php

namespace App\Components;

use Carbon\Carbon;

class Fast
{
    public $id;
    public $status;
    public $type;
    public $start_date;
    public $end_date;

    public function __construct($fast)
    {
        $this->setFast($fast);
    }

    public function setFast($fast)
    {
        $this->id = $fast->id;
        $this->status = $fast->status;
        $this->type = $fast->type;
        $this->start_date = Carbon::parse($fast->start_date);
        $this->end_date = Carbon::parse($fast->end_date);
    }

    public function getElapsedTime()
    {
        if($this->isStartDateInFuture()){
            return "The fast is not started yet.";
        }

        $seconds = Carbon::now()->diffInSeconds($this->getStartDate());
        $elapsedTime = transformSeconds($seconds);
        return $elapsedTime;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getStartDate()
    {
        return Carbon::parse($this->start_date)->format('d M Y H:i:s');
    }

    public function getEndDate()
    {
        return Carbon::parse($this->end_date)->format('d M Y H:i:s');
    }

    public function getType()
    {
        return $this->type . ' hour fast';
    }

    protected function isStartDateInFuture()
    {
        return $this->start_date->isPast() ? false : true;
    }
}
