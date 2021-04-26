<?php

namespace App\Components;

class AppOutput
{
    /**
     * @var ConsoleOutput $output
     */
    public $output;

    /**
     * @var ConsoleOutputColorDecorator $output_decorator
     */
    public $output_decorator;

    /**
     * AppOutput Constructor
     *
     * @param ConsoleOutput $outpu
     * @param ConsoleOutputColorDecorator $output_decorator
     */
    public function __construct($output, $output_decorator)
    {
        $this->output = $output;
        $this->output_decorator = $output_decorator;
    }

    /**
     * Prints application menu in the terminal
     *
     * @param array $options
     * @return void
     */
    public function displayMenuOptions($options): void
    {
        $this->output->write(
            PHP_EOL .
                $this->output_decorator->decorateWhite('--------------------------------') . PHP_EOL .
                $this->output_decorator->decorateWhite("| Menu Options") . PHP_EOL .
                $this->output_decorator->decorateWhite('--------------------------------')
        );

        foreach ($options as $key => $option) {
            $this->output->write(
                $this->output_decorator->decorateYellow("{$key}. ") .
                    $this->output_decorator->decorateWhite("{$option}")
            );
        }
    }

    /**
     * Print error message when wrong Option is selected
     *
     * @return void
     */
    public function invalidMenuOptionSelected(): void
    {
        $this->output->write(
            $this->output_decorator->decorateRed("Please choose an option from the menu!")
        );
    }

    /**
     * Print warning message if user selects 
     * unavailable option when not fasting
     *
     * @return void
     */
    public function displayUserNotFastingMessage(): void
    {
        $this->output->write(
            $this->output_decorator->decorateYellow("You don't have an active fast at the moment.")
        );
    }

    /**
     * Print warning message if user selects 
     * unavailable option when fasting
     *
     * @return void
     */
    public function displayUserFastingMessage(): void
    {
        $this->output->write(
            $this->output_decorator->decorateRed("You already have an active fast!")
        );
    }

    /**
     * Display formatted data for a single post
     *
     * @param Fast $fast
     * @return void
     */
    public function displayFastData($fast): void
    {
        $statusColor = $fast->status == 'active' ? 'decorateGreen' : 'decorateRed';
        $this->output->write(
            $this->output_decorator->decorateBlue('Fast Information:') . PHP_EOL .
                $this->output_decorator->decorateWhite("Status: ") .
                $this->output_decorator->$statusColor($fast->getStatus()) . PHP_EOL .
                $this->output_decorator->decorateWhite("Start date: ") .
                $this->output_decorator->decorateGreen($fast->getStartDate()) . PHP_EOL .
                $this->output_decorator->decorateWhite("End date: ") .
                $this->output_decorator->decorateGreen($fast->getEndDate()) . PHP_EOL .
                $this->output_decorator->decorateWhite("Time Elapsed: ") .
                $this->output_decorator->decorateGreen($fast->getElapsedTime()) . PHP_EOL .
                $this->output_decorator->decorateWhite("Fast type: ") .
                $this->output_decorator->decorateGreen($fast->getType()) . PHP_EOL
        );
    }

    /**
     * Print message to ask the user 
     * to enter start date for the fast
     *
     * @return void
     */
    public function askForStartDate(): void
    {
        $this->output->write(
            $this->output_decorator->decorateYellow("Please enter a start date for your fast. ") .
                $this->output_decorator->decorateBlue('[Y/m/d h:m:s]')
        );
    }

    /**
     * Print error message when the user entered invalid date
     *
     * @return void
     */
    public function invalidStartDate(): void
    {
        $this->output->write(
            $this->output_decorator->decorateRed("Invalid date.")
        );
    }

    /**
     * Print error message when the entered date is in past
     *
     * @return void
     */
    public function pastDateEntered(): void
    {
        $this->output->write(
            $this->output_decorator->decorateRed("Please enter date in the future.")
        );
    }

    /**
     * Ask the user for fast type
     *
     * @return void
     */
    public function askForFastType(): void
    {
        $this->output->write(
            $this->output_decorator->decorateYellow("Please enter your fast type. ") .
                $this->output_decorator->decorateWhite('Possible values: 16, 18, 20, 36')
        );
    }

    /**
     * Print error message for invalid fast type
     *
     * @return void
     */
    public function invalidFastTypeMessage(): void
    {
        $this->output->write(
            $this->output_decorator->decorateRed("Please enter one of the types specified above.")
        );
    }

    /**
     * Notifies the user that the fast was successfully created
     *
     * @return void
     */
    public function fastAddedFeedback(): void
    {
        $this->output->write(
            $this->output_decorator->decorateGreen("Your fast has been successfully created.")
        );
    }

    /**
     * Notifies the user that the fast was successfully ended
     *
     * @return void
     */
    public function fastEndedFeddback(): void
    {
        $this->output->write(
            $this->output_decorator->decorateGreen("Your fast has been successfully ended.")
        );
    }


    /**
     * Notifies the user that the fast type and 
     * start date were successfully updated
     *
     * @return void
     */
    public function fastUpdatedFeedback(): void
    {
        $this->output->write(
            $this->output_decorator->decorateGreen("Your fast details has been successfully updated.")
        );
    }

    /**
     * Prints all the fasts in a formatted table
     *
     * @return void
     */
    public function listAllFasts($fasts): void
    {
        $this->output->write(
            $this->output_decorator->decorateWhite('----------------------------------------------') . PHP_EOL .
                $this->output_decorator->decorateBlue('Fasting History') . PHP_EOL .
                $this->output_decorator->decorateWhite('----------------------------------------------') . PHP_EOL
        );
        foreach ($fasts as $fast) {
            $statusColor = $fast->status == 'active' ? 'decorateGreen' : 'decorateRed';
            $this->output->write(
                $this->output_decorator->decorateWhite("Status: ") .
                    $this->output_decorator->$statusColor($fast->getStatus()) . PHP_EOL .
                    $this->output_decorator->decorateWhite("Start date: ") .
                    $this->output_decorator->decorateGreen($fast->getStartDate()) . PHP_EOL .
                    $this->output_decorator->decorateWhite("End date: ") .
                    $this->output_decorator->decorateGreen($fast->getEndDate()) . PHP_EOL .
                    $this->output_decorator->decorateWhite("Time Elapsed: ") .
                    $this->output_decorator->decorateGreen($fast->getElapsedTime()) . PHP_EOL .
                    $this->output_decorator->decorateWhite("Fast type: ") .
                    $this->output_decorator->decorateGreen($fast->getType()) . PHP_EOL .
                    $this->output_decorator->decorateWhite('----------------------------------------------')
            );
        }
    }

    /**
     * Prints to screen when the fasting history is empty
     *
     * @return void
     */
    public function noFastsAvailable(): void
    {
        $this->output->write(
            $this->output_decorator->decorateBlue("Your fasting history is empty.")
        );
    }

    /**
     * Prints information about
     * the application and the author
     *
     * @return void
     */
    public function initMessage(): void
    {
        $this->output->write(
            $this->output_decorator->decorateRed('-------------------------------------------------------------') . PHP_EOL .
                $this->output_decorator->decorateGreen("Quantox Console Application - Intermittent Fasting Tracker") . PHP_EOL .
                $this->output_decorator->decorateBlue('built by: ') .
                $this->output_decorator->decorateWhite('Daniel Stanojkov') . PHP_EOL .
                $this->output_decorator->decorateRed('-------------------------------------------------------------')
        );
    }

    /**
     * Asks the user for confirmation
     * on ending a fast
     *
     * @return void
     */
    public function confirmFastCancelation(): void
    {
        $this->output->write(
            $this->output_decorator->decorateYellow("Are you sure you want to cancel the current fast? ") .
                $this->output_decorator->decorateGreen('N') .
                $this->output_decorator->decorateWhite(' / ') .
                $this->output_decorator->decorateRed('Y')
        );
    }

    /**
     * Prints to console that fast cancelation is aborted
     *
     * @return void
     */
    public function fastEndingCancelled(): void
    {
        $this->output->write(
            $this->output_decorator->decorateYellow("Fast cancelation aborted.")
        );
    }
}
