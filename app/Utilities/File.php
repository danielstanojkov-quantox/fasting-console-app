<?php

namespace App\Utilities;

class File
{
    public static function exists($filename)
    {
        return file_exists($filename);
    }

    public static function createDatabase()
    {
        self::makeDirectory('database');
        self::makeFile('database', 'fasts.json', '[]');
    }

    public static function makeDirectory($dirname)
    {
        mkdir(APP_ROOT . "/$dirname");
    }

    public static function makeFile($directory, $filename, $content)
    {
        $file = fopen("$directory/$filename", "w");
        fwrite($file, $content);
        fclose($file);
    }
}
