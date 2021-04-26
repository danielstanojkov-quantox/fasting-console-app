<?php

namespace App\Components;

class AppOutput
{
    public $output;
    public $output_decorator;

    public function __construct($output, $output_decorator)
    {
        $this->output = $output;
        $this->output_decorator = $output_decorator;
    }

    public function displayMenuOptions($options)
    {
        foreach ($options as $key => $option) {
            $this->output->write(
                $this->output_decorator->decorateYellow("{$key}. ") .
                    $this->output_decorator->decorateWhite("{$option}")
            );
        }
    }

    public function invalidMenuOptionSelected()
    {
        $this->output->write(
            $this->output_decorator->decorateRed("Please choose an option from the menu!")
        );
    }

    public function displayUserNotFastingMessage()
    {
        $this->output->write(
            $this->output_decorator->decorateYellow("You don't have an active fast at the moment.")
        );
    }

    public function displayUserFastingMessage()
    {
        $this->output->write(
            $this->output_decorator->decorateRed("You already have an active fast!")
        );
    }

    public function displayFastData($fast)
    {
        $this->output->write(
            $this->output_decorator->decorateBlue('Fast Information:') . PHP_EOL .
            $this->output_decorator->decorateWhite("Status: ") .
            $this->output_decorator->decorateGreen($fast->getStatus()) . PHP_EOL .
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

    public function askForStartDate()
    {
        $this->output->write(
            $this->output_decorator->decorateYellow("Please enter a start date for your fast. ") . 
            $this->output_decorator->decorateBlue('[Y/m/d h:m:s]')
        );
    }

    public function invalidStartDate()
    {
        $this->output->write(
            $this->output_decorator->decorateRed("Invalid date.")
        );
    }

    public function pastDateEntered()
    {
        $this->output->write(
            $this->output_decorator->decorateRed("Please enter date in the future.")
        );
    }


    public function askForFastType()
    {
        $this->output->write(
            $this->output_decorator->decorateYellow("Please enter your fast type. ") . 
            $this->output_decorator->decorateWhite('Possible values: 16, 18, 20, 36')
        );
    }


    public function invalidFastTypeMessage()
    {
        $this->output->write(
            $this->output_decorator->decorateRed("Please enter one of the types specified above.")
        );
    }


    public function fastAddedFeedback()
    {
        $this->output->write(
            $this->output_decorator->decorateGreen("Your fast has been successfully created.")
        );
    }

    public function fastEndedFeddback()
    {
        $this->output->write(
            $this->output_decorator->decorateGreen("Your fast has been successfully ended.")
        );
    }

    public function fastUpdatedFeedback()
    {
        $this->output->write(
            $this->output_decorator->decorateGreen("Your fast details has been successfully updated.")
        ); 
    }
}
