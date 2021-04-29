<?php

/**
 * Convert provided seconds into
 * hours, minutes and seconds
 *
 * @param int $n
 * @return string
 */
function transformSeconds($n): string
{
    $day = floor($n / (24 * 3600));

    $n = ($n % (24 * 3600));
    $hour = $n / 3600;

    $n %= 3600;
    $minutes = $n / 60;

    $n %= 60;
    $seconds = $n;

    $day = round($day, 0);
    $hour = round($hour, 0);
    $minutes = round($minutes, 0);
    $seconds = round($seconds, 0);

    $string = $day > 0 ? "$day days " : '';
    $string = $hour > 0 ? $string . "$hour hours " : '';
    $string = $minutes > 0 ? $string . "$minutes minutes " : '';
    $string = $string . "$seconds seconds";

    return $string;
}


/**
 * Make an one based array
 *
 * @param array $array
 * @return array
 */
function reindexArray($array): array
{
    return array_combine(
        range(1, count($array)),
        array_values($array)
    );
}
