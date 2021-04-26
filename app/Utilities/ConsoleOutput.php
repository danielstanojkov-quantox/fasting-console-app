<?php

namespace App\Utilities;

class ConsoleOutput
{
    /**
     * Write output
     * 
     * @param string $output
     * @return void 
     */
    public function write($output): void
    {
        echo $output . PHP_EOL;
    }
}
