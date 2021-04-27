<?php

namespace App;

use Carbon\Carbon;
use App\API\RandomQuote;
use Exception;

class Tracker
{
    /**
     * 
     * @var ConsoleInput $input
     */
    public $input;

    /**
     * 
     * @var AppOutput $output
     */
    public $output;

    /**
     * Establishes connection with our db
     *
     * @var object $fastModel
     */
    public $fastModel;

    /**
     * All available options on the meny
     *
     * @var array
     */
    public $menu_options = [
        1 => 'Check the fast status',
        2 => 'Start a fast ( available only if there is not an active fast)',
        3 => 'End an active fast (available only if there is an active fast)',
        4 => 'Update an active fast (available only if there is an active fast)',
        5 => 'List all fasts'
    ];

    /**
     * Tracker Constructor
     *
     * @param ConsoleInput $input
     * @param AppOutput $output
     */
    public function __construct($input, $output)
    {
        $this->input = $input;
        $this->output = $output;

        $this->output->initMessage();
        $this->loadModels();
        $this->init();
    }

    /**
     * Loads all neseeccarry models 
     *
     * @return void
     */
    public function loadModels(): void
    {
        $fastModel = MODELS_NAMESPACE . 'Fast';
        $this->fastModel = new $fastModel;
    }

    /**
     * Reverts the application to initial state
     *
     * @return void
     */
    public function init(): void
    {
        $this->displayMenu();
        $this->promptUserForOption();
    }

    /**
     * Displays the menu in terminal
     *
     * @return void
     */
    public function displayMenu(): void
    {
        $this->output->displayMenuOptions($this->menu_options);
    }

    /**
     * Prompts the user to select option from the menu
     *
     * @return void
     */
    public function promptUserForOption(): void
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

    /**
     * Checks if option exists in the menu
     *
     * @param string $option
     * @return bool
     */
    protected function checkIfOptionExists($option): bool
    {
        return in_array($option, array_keys($this->menu_options), true) ? true : false;
    }

    /**
     * Process the selected option
     *
     * @param string $option
     * @return void
     */
    public function proccessSelectedOption($option): void
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

    /**
     * 1. Check current status option selected
     *
     * @return void
     */
    public function checkFastStatus(): void
    {
        $this->printInspirationalQuote();

        $this->fastModel->isUserFasting()
            ? $this->output->displayFastData($this->fastModel->active_fast)
            : $this->handleNoFastingState();

        $this->init();
    }

    /**
     * Fetches and prints random quote
     *
     * @return void
     */
    public function printInspirationalQuote(): void
    {
        $quote = RandomQuote::get();
        $this->output->printQuote($quote);
    }

    /**
     * 2. Start Fast option selected
     *
     * @return void
     */
    public function startFast(): void
    {
        $this->fastModel->isUserFasting()
            ? $this->handleFastingState()
            : $this->promptForFastDetails();
    }

    /**
     * Prompts the user to enter fast details
     *
     * @return void
     */
    protected function promptForFastDetails(): void
    {
        $start_date = $this->promptForStartDate();
        $fast_type = $this->promptForFastType();

        $this->saveActiveFast($start_date, $fast_type);
        $this->init();
    }

    /**
     * Saves the fast in database
     *
     * @param string $start_date
     * @param string $fast_type
     * @return void
     */
    private function saveActiveFast($start_date, $fast_type): void
    {
        $this->fastModel->saveActiveFast($start_date, $fast_type);
        $this->output->fastAddedFeedback();
    }


    /**
     * 3. Ends an active fast
     *
     * @return void
     */
    public function endActiveFast(): void
    {
        if ($this->fastModel->isUserFasting()) {
            if ($this->confirmFastCancelation()) {
                $this->fastModel->endActiveFast();
                $this->output->fastEndedFeddback();
            } else {
                $this->output->fastEndingCancelled();
            }
        } else {
            $this->handleNoFastingState();
        }

        $this->init();
    }

    /**
     * Confirm fast cancellation
     *
     * @return bool
     */
    protected function confirmFastCancelation(): bool
    {
        $this->output->confirmFastCancelation();

        $final_answer = null;
        $possible_answers = ['n', 'N', 'y', 'Y'];

        while (!$final_answer) {
            $answer = $this->input->read();
            if (in_array($answer, $possible_answers)) {
                $final_answer = $answer;

                return ($final_answer == 'n' || $final_answer == 'N')
                    ? false
                    : true;
            }
        }
    }

    /**
     * 4. User selects to update the active post
     *
     * @return void
     */
    public function updateActiveFast(): void
    {
        if ($this->fastModel->isUserFasting()) {
            $this->askForNewFastDetails();
            $this->output->fastUpdatedFeedback();
        } else {
            $this->handleNoFastingState();
        }

        $this->init();
    }

    /**
     * Prompting the user for new fast details
     *
     * @return void
     */
    protected function askForNewFastDetails(): void
    {
        $start_date = $this->promptForStartDate();
        $fast_type = $this->promptForFastType();

        $this->saveUpdatedFast($start_date, $fast_type);
    }

    protected function saveUpdatedFast($start_date, $fast_type)
    {
        $this->fastModel->updateActiveFast($start_date, $fast_type);
    }

    /**
     * 5. Print all fasts from db in tabular form
     *
     * @return void
     */
    public function listAllFasts(): void
    {
        $all_fasts = $this->fastModel->all_fasts;

        if (count($all_fasts) > 0) {
            $this->output->listAllFasts($all_fasts);
        } else {
            $this->output->noFastsAvailable();
        }

        $this->init();
    }

    /**
     * Enter starting date for the fast
     *
     * @return Carbon $start_date
     */
    protected function promptForStartDate(): Carbon
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

    /**
     * Enter fast type
     *
     * @return string $fast_type
     */
    protected function promptForFastType(): string
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


    /**
     * User selects option which is currently unavailable
     * because the user doesn't have an active fast
     *
     * @return void
     */
    private function handleNoFastingState(): void
    {
        $this->output->displayUserNotFastingMessage();
    }


    /**
     * User selects option which is currently unavailable
     * because the user have an active fast
     *
     * @return void
     */
    private function handleFastingState(): void
    {
        $this->output->displayUserFastingMessage();
        $this->init();
    }
}
