<?php

namespace App\Components;

use Carbon\Carbon;

class Fast
{
    /**
     * Fast unique identifier
     *
     * @var int $id
     */
    public $id;

    /**
     * Fast status
     *
     * @var string $status
     */
    public $status;


    /**
     * Fast type.
     * Example: 24 hour fast
     *
     * @var int $type
     */
    public $type;

    /**
     * Fast starting date
     *
     * @var Carbon $start_date
     */
    public $start_date;

    /**
     * Fast ending date
     *
     * @var Carbon $end_date
     */
    public $end_date;

    /**
     * Fast Constructor
     *
     * @param object $fast
     */
    public function __construct($fast)
    {
        $this->setFast($fast);
    }

    /**
     * Set all the properties to the fast instance
     *
     * @param object $fast
     * @return void
     */
    public function setFast($fast): void
    {
        $this->id = $fast->id;
        $this->status = $fast->status;
        $this->type = $fast->type;
        $this->start_date = Carbon::parse($fast->start_date);
        $this->end_date = Carbon::parse($fast->end_date);
    }

    /**
     * Get elapsed time of a particular fast
     *
     * @return string $elapsedTime;
     */
    public function getElapsedTime(): string
    {
        if ($this->isStartDateInFuture()) {
            return "The fast is not started yet.";
        }

        $seconds = Carbon::now()->diffInSeconds($this->getStartDate());
        $elapsedTime = transformSeconds($seconds);
        return $elapsedTime;
    }

    /**
     * Get the status of a fast
     *
     * @return string 
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Get the started date of a fast
     *
     * @return string
     */
    public function getStartDate(): string
    {
        return Carbon::parse($this->start_date)->format('d M Y H:i:s');
    }


    /**
     * Get the ending date of a fast
     *
     * @return string
     */
    public function getEndDate(): string
    {
        return Carbon::parse($this->end_date)->format('d M Y H:i:s');
    }

    /**
     * Get fast type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type . ' hour fast';
    }

    /**
     * Checks if the starting date is in the past
     *
     * @return boolean
     */
    protected function isStartDateInFuture(): bool
    {
        return $this->start_date->isPast() ? false : true;
    }
}
