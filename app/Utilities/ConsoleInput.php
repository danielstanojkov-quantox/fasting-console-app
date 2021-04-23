<?php

namespace App\Utilities;

class ConsoleInput
{
    public function read()
    {
        return trim(fgets(STDIN));
    }
}