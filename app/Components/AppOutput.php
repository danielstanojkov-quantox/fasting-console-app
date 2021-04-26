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
            $this->output_decorator->decorateYellow("You don't have an active fast currently.")
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
}
