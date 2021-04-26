<?php

namespace App;

use Carbon\Carbon;
use Exception;

class Tracker
{
    public $input;
    public $output;

    public $menu_options = [
        1 => 'Check the fast status',
        2 => 'Start a fast ( available only if there is not an active fast)',
        3 => 'End an active fast (available only if there is an active fast)',
        4 => 'Update an active fast (available only if there is an active fast)',
        5 => 'List all fasts'
    ];

    // Models
    public $fastModel;

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

    // Check Fast Status
    public function checkFastStatus()
    {
        $this->fastModel->isUserFasting()
            ? $this->output->displayFastData($this->fastModel->active_fast)
            : $this->handleNoFastingState();

        $this->init();
    }

    // Start Fast
    public function startFast()
    {
        $this->fastModel->isUserFasting()
            ? $this->handleFastingState()
            : $this->promptForFastDetails();
    }

    protected function promptForFastDetails()
    {
        $this->saveActiveFast(
            $this->promptForStartDate(),
            $this->promptForFastType()
        );

        $this->init();
    }

    private function saveActiveFast($start_date, $fast_type)
    {
        $this->fastModel->saveActiveFast($start_date, $fast_type);
        $this->output->fastAddedFeedback();
    }


    // End Active Fast
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

    // Update an active fast 
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
        $this->saveUpdatedFast(
            $this->promptForStartDate(),
            $this->promptForFastType()
        );
    }

    protected function saveUpdatedFast($start_date, $fast_type)
    {
        $this->fastModel->updateActiveFast($start_date, $fast_type);
    }


    protected function promptForStartDate()
    {
        $this->output->askForStartDate();
        return $this->askForStartDate();
    }

    protected function promptForFastType()
    {
        $this->output->askForFastType();
        return $this->askForFastType();
    }


    private function askForStartDate()
    {
        $date = $this->input->read();

        try {
            $date = Carbon::parse($date);
            if ($date->isPast()) throw new Exception();
        } catch (\Throwable $e) {
            $this->output->invalidStartDate();
            $this->askForStartDate();
        }

        return $date;
    }

    private function askForFastType()
    {
        $available_types = [16, 18, 20, 36];

        $type = $this->input->read();

        if (!in_array($type, $available_types)) {
            $this->output->invalidFastTypeMessage();
            $this->askForFastType();
        }

        return $type;
    }





    private function handleNoFastingState()
    {
        $this->output->displayUserNotFastingMessage();
    }

    private function handleFastingState()
    {
        $this->output->displayUserFastingMessage();
        $this->init();
    }

    public function listAllFasts()
    {
        echo 'all fasts';
    }
}
