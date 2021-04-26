<?php

namespace App\Utilities;


class ConsoleOutputColorDecorator
{
    /**
     * All colors used to style terminal lines
     *
     * @var array
     */
    private $colors_map = [
        'BLUE' => '0;34',
        'RED' => '0;31',
        'YELLOW' => '1;33',
        'GREEN' => '0;32',
        'WHITE' => '1;37'
    ];

    /**
     * Decorate Yellow
     *
     * @param string $output
     * @return string
     */
    public function decorateYellow(string $output): string
    {
        return $this->decorateStringColors($output, 'YELLOW');
    }

    /**
     * Decorate Blue
     *
     * @param string $output
     * @return string
     */
    public function decorateBlue(string $output): string
    {
        return $this->decorateStringColors($output, 'BLUE');
    }

    /**
     * Decorate White
     *
     * @param string $output
     * @return string
     */
    public function decorateWhite(string $output): string
    {
        return $this->decorateStringColors($output, 'WHITE');
    }

    /**
     * Decorate Red
     *
     * @param string $output
     * @return string
     */
    public function decorateRed(string $output): string
    {
        return $this->decorateStringColors($output, 'RED');
    }

    /**
     * Decorate Green
     *
     * @param string $output
     * @return string
     */
    public function decorateGreen(string $output): string
    {
        return $this->decorateStringColors($output, 'GREEN');
    }

    /**
     * Apply color to provided text
     *
     * @param string $text
     * @param string $color
     * @return string
     */
    private function decorateStringColors(string $text, string $color): string
    {
        $color = $this->colors_map[strtoupper($color)];

        return "\e[{$color}m{$text}\e[0m";
    }
}
