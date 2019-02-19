<?php

namespace Classes;

use Interfaces\Classes\StorageInterface;
use \Exception;

class Storage implements StorageInterface
{

    const FILE_NAME = 'report.csv';

    const STORAGE_PATH = './Resources/Storage/';

    /**
     * Downloads file and returns a file pointer resource
     *
     * @param string    $url    File url
     *
     * @return mixed
     */
    public static function getFile(string $url)
    {
        set_time_limit(0);

        $fileWrite = fopen(self::STORAGE_PATH . self::FILE_NAME, 'w+');

        $fileRead = fopen($url, 'r');
        if ($fileRead) {
            while (($line = fgets($fileRead)) !== false) {
                fwrite($fileWrite, $line);
            }

            fclose($fileRead);
            fclose($fileWrite);
        } else {
            throw new Exception('Can\'t open file');
        }

        return fopen(self::STORAGE_PATH . self::FILE_NAME, 'r');
    }

}
