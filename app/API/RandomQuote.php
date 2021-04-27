<?php

namespace App\API;

class RandomQuote 
{
    /**
     * Fetches a random inspirational quote
     *
     * @return object
     */
    public static function get(): object
    {
        return json_decode(file_get_contents(QUOTE_API_ENDPOINT));
    }
}