<?php

namespace App\Utilities;


class ConsoleOutputColorDecorator
{
    private $colors_map = [
        'BLUE' => '0;34',
        'RED' => '0;31',
        'YELLOW' => '1;33',
        'GREEN' => '0;32',
        'WHITE' => '1;37'
    ];


    public function decorateYellow(string $output): string
    {
        return $this->decorateStringColors($output, 'YELLOW');
    }


    public function decorateBlue(string $output): string
    {
        return $this->decorateStringColors($output, 'BLUE');
    }


    public function decorateWhite(string $output): string
    {
        return $this->decorateStringColors($output, 'WHITE');
    }


    public function decorateRed(string $output): string
    {
        return $this->decorateStringColors($output, 'RED');
    }

    public function decorateGreen(string $output): string
    {
        return $this->decorateStringColors($output, 'GREEN');
    }


    private function decorateStringColors(string $text, string $color): string
    {
        $color = $this->colors_map[strtoupper($color)];

        return "\e[{$color}m{$text}\e[0m";
    }
}
