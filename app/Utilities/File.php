<?php

namespace App\Utilities;

class File
{
    /**
     * Checks if file exists
     *
     * @param string $filename
     * @return bool
     */
    public static function exists($filename): bool
    {
        return file_exists($filename);
    }

    /**
     * Creates database in our folder structure
     *
     * @return void
     */
    public static function createDatabase(): void
    {
        self::makeDirectory('database');
        self::makeFile('database', 'fasts.json', '[]');
    }

    /**
     * Create folder
     *
     * @param string $dirname
     * @return void
     */
    public static function makeDirectory($dirname): void
    {
        mkdir(APP_ROOT . "/$dirname");
    }

    /**
     * Create File
     *
     * @param string $directory
     * @param string $filename
     * @param string $content
     * @return void
     */
    public static function makeFile($directory, $filename, $content): void
    {
        $file = fopen("$directory/$filename", "w");
        fwrite($file, $content);
        fclose($file);
    }
}
