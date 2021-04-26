<?php

namespace App;

use Carbon\Carbon;
use Exception;

class Tracker
{
    public $input;
    public $output;

    public $fastModel;

    public $menu_options = [
        1 => 'Check the fast status',
        2 => 'Start a fast ( available only if there is not an active fast)',
        3 => 'End an active fast (available only if there is an active fast)',
        4 => 'Update an active fast (available only if there is an active fast)',
        5 => 'List all fasts'
    ];

    public function __construct($input, $output)
    {
        $this->input = $input;
        $this->output = $output;

        $this->loadModels();
        $this->init();
    }

    public function loadModels()
    {
        $fastModel = MODELS_NAMESPACE . 'Fast';
        $this->fastModel = new $fastModel;
    }

    public function init()
    {
        $this->displayMenu();
        $this->promptUserForOption();
    }

    public function displayMenu()
    {
        $this->output->displayMenuOptions($this->menu_options);
    }

    public function promptUserForOption()
    {
        $option = $this->input->read();
        $option = is_numeric($option) ? +$option : $option;

        if (!$this->checkIfOptionExists($option)) {
            $this->output->invalidMenuOptionSelected();
            $this->promptUserForOption();
            return;
        }

        $this->proccessSelectedOption($option);
    }

    protected function checkIfOptionExists($option)
    {
        return in_array($option, array_keys($this->menu_options), true) ? true : false;
    }


    public function proccessSelectedOption($option)
    {
        switch ($option) {
            case '1':
                $this->checkFastStatus();
                break;
            case '2':
                $this->startFast();
                break;
            case '3':
                $this->endActiveFast();
                break;
            case '4':
                $this->updateActiveFast();
                break;
            case '5':
                $this->listAllFasts();
        }
    }

    // 1. Check Fast Status
    public function checkFastStatus()
    {
        $this->fastModel->isUserFasting()
            ? $this->output->displayFastData($this->fastModel->active_fast)
            : $this->handleNoFastingState();

        $this->init();
    }

    // 2. Start Fast
    public function startFast()
    {
        $this->fastModel->isUserFasting()
            ? $this->handleFastingState()
            : $this->promptForFastDetails();
    }

    protected function promptForFastDetails()
    {
        $start_date = $this->promptForStartDate();
        $fast_type = $this->promptForFastType();

        $this->saveActiveFast($start_date, $fast_type);
        $this->init();
    }

    private function saveActiveFast($start_date, $fast_type)
    {
        $this->fastModel->saveActiveFast($start_date, $fast_type);
        $this->output->fastAddedFeedback();
    }


    // 3. End Active Fast
    public function endActiveFast()
    {
        if ($this->fastModel->isUserFasting()) {
            $this->fastModel->endActiveFast();
            $this->output->fastEndedFeddback();
        } else {
            $this->handleNoFastingState();
        }

        $this->init();
    }

    // 4. Update an active fast 
    public function updateActiveFast()
    {
        if ($this->fastModel->isUserFasting()) {
            $this->askForNewFastDetails();
            $this->output->fastUpdatedFeedback();
        } else {
            $this->handleNoFastingState();
        }

        return $this->init();
    }

    protected function askForNewFastDetails()
    {
        $start_date = $this->promptForStartDate();
        $fast_type = $this->promptForFastType();

        $this->saveUpdatedFast($start_date, $fast_type);
    }

    protected function saveUpdatedFast($start_date, $fast_type)
    {
        $this->fastModel->updateActiveFast($start_date, $fast_type);
    }


    // 5. List All Fasts
    public function listAllFasts()
    {
        $all_fasts = $this->fastModel->all_fasts;

        if (count($all_fasts) > 0) {
            $this->output->listAllFasts($all_fasts);
        } else {
            $this->output->noFastsAvailable();
        }

        $this->init();
    }

    protected function promptForStartDate()
    {
        $this->output->askForStartDate();
        $start_date = null;

        while (!$start_date) {

            $date = $this->input->read();

            try {
                $date = Carbon::parse($date);
                if ($date->isPast()) {
                    throw new Exception();
                } else {
                    $start_date = $date;
                    return $start_date;
                }
            } catch (\Throwable $e) {
                $this->output->invalidStartDate();
                continue;
            }
        }
    }

    protected function promptForFastType()
    {
        $this->output->askForFastType();

        $available_types = [16, 18, 20, 36];
        $fast_type = null;

        while (!$fast_type) {

            $type = $this->input->read();

            if (!in_array($type, $available_types)) {
                $this->output->invalidFastTypeMessage();
                continue;
            } else {
                $fast_type = $type;
                return $fast_type;
            }
        }
    }


    // Output fasting state

    private function handleNoFastingState()
    {
        $this->output->displayUserNotFastingMessage();
    }


    private function handleFastingState()
    {
        $this->output->displayUserFastingMessage();
        $this->init();
    }
}
