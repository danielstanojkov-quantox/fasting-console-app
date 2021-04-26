<?php

namespace App;

use App\Utilities\ConsoleInput;
use App\Utilities\ConsoleOutput;
use App\Utilities\ConsoleOutputColorDecorator;
use App\Components\AppOutput;

class Application
{
    /**
     * Boot the application
     * 
     * @return Tracker
     */
    public function boot(): Tracker
    {
        $output = new AppOutput(new ConsoleOutput, new ConsoleOutputColorDecorator);

        return new Tracker(new ConsoleInput, $output);
    }
}
