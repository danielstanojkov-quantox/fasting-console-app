<?php

namespace App\Models;

use App\Components\Fast as FastInstance;
use Carbon\Carbon;

class Fast
{
    /**
     * All fasts available in the database
     *
     * @var array $all_fasts
     */
    public $all_fasts;

    /**
     * Holds the current active fast or null
     *
     * @var object|null $active_fast
     */
    public $active_fast;

    /**
     * Fast model constructor
     */
    public function __construct()
    {
        $this->loadFasts();
        $this->setActiveFast();
    }

    /**
     * Loads and sets all the fasts from database 
     * and map them to a FastInstance Class
     * 
     * @return void
     */
    protected function loadFasts(): void
    {
        $fasts = json_decode(file_get_contents(APP_DB));
        $mapped_fasts = array_map(function ($fast) {
            return new FastInstance($fast);
        }, $fasts);

        $this->all_fasts = $mapped_fasts;
    }

    /**
     * Finds and set the current active fast if 
     * the user is fasting.
     * 
     * @return void
     */
    protected function setActiveFast(): void
    {
        $fast = array_filter($this->all_fasts, function ($fast) {
            return $fast->status == 'active';
        });

        $this->active_fast = count($fast) > 0 ? array_pop($fast) : null;
    }

    /**
     * Checks if the user is fasting
     *
     * @return boolean
     */
    public function isUserFasting(): bool
    {
        return $this->active_fast ? true : false;
    }

    /**
     * Creates a new fast record in the database
     *
     * @param [Carbon] $start_date
     * @param string $fast_type
     * @return void
     */
    public function saveActiveFast($start_date, $fast_type): void
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

        $this->syncronizeDatabase();
    }

    /**
     * Ends an active fast and updates the database
     *
     * @return void
     */
    public function endActiveFast(): void
    {
        $fasts = file_get_contents(APP_DB);
        $updated_fasts = str_replace('"active"', '"inactive"', $fasts);
        file_put_contents(APP_DB, $updated_fasts);

        $this->syncronizeDatabase();
    }

    /**
     * Update existing fast and submit it to database
     *
     * @param [Carbon] $start_date
     * @param string $fast_type
     * @return void
     */
    public function updateActiveFast($start_date, $fast_type): void
    {
        $end_date = Carbon::parse($start_date)->addHours((int)$fast_type);
        $fasts = json_decode(file_get_contents(APP_DB));

        $updated_fasts = array_map(function ($fast) use ($start_date, $end_date, $fast_type) {
            return $fast->status !== 'active'
                ? $fast
                : [
                    'id' => $fast->id,
                    'status' => $fast->status,
                    'type' => $fast_type,
                    'start_date' => $start_date,
                    'end_date' => $end_date
                ];
        }, $fasts);

        file_put_contents(APP_DB, json_encode($updated_fasts));

        $this->syncronizeDatabase();
    }

    /**
     * Syncronizes the database with current data 
     * from the properties
     *
     * @return void
     */
    private function syncronizeDatabase(): void
    {
        $this->loadFasts();
        $this->setActiveFast();
    }
}
