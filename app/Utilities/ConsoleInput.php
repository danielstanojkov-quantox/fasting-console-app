<?php

namespace App\Utilities;

class ConsoleInput
{
    /**
     * Read console input
     *
     * @return string
     */
    public function read(): string
    {
        return trim(fgets(STDIN));
    }
}
