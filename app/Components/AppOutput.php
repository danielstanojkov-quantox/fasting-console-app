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
    
}
