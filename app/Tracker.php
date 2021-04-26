<?php

namespace App;

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

    public function checkFastStatus()
    {
        $this->fastModel->isUserFasting()
            ? $this->output->displayFastData($this->fastModel->active_fast)
            : $this->handleNoFastingState();
    }

    private function handleNoFastingState()
    {
        $this->output->displayUserNotFastingMessage();
        $this->init();
    }








    public function startFast()
    {

        echo 'start fast';
    }

    public function endActiveFast()
    {
        echo 'end active fast';
    }

    public function updateActiveFast()
    {
        echo 'update active fast';
    }

    public function listAllFasts()
    {
        echo 'all fasts';
    }
}
