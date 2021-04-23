<?php

namespace App;

use App\Utilities\ConsoleInput;
use App\Utilities\ConsoleOutput;
use App\Utilities\ConsoleOutputColorDecorator;
use App\Components\AppOutput;

class Application
{
    public function boot()
    {
        $output = new AppOutput(new ConsoleOutput, new ConsoleOutputColorDecorator);

        return new Tracker(new ConsoleInput, $output);
    }
}