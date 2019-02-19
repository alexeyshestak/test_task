<?php

namespace Interfaces\Classes;

interface StorageInterface
{

    /**
     * Downloads file and returns a file pointer resource
     *
     * @param string    $url    File url
     *
     * @return mixed
     */
    public static function getFile(string $url);

}
