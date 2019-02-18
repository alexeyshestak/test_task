<?php

namespace Classes;

class Storage
{

    /** @var PDO Instance */
    protected static $instance;

    /**
     * Get DB instance
     *
     * @return PDO
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {

            $dbConfig = config('db');

            try {
                self::$instance = new PDO(
                    'mysql:host=' . $dbConfig['host'] . ';port=' . $dbConfig['port'] . ';dbname=' . $dbConfig['db'],
                    $dbConfig['user'],
                    $dbConfig['pass']
                );

                self::$instance
                    ->setAttribute(
                        PDO::ATTR_ERRMODE,
                        PDO::ERRMODE_SILENT
                    );

                self::$instance
                    ->query('SET NAMES utf8');

                self::$instance
                    ->query('SET character_set_connection=utf8');

                self::$instance
                    ->query('SET character_set_client=utf8');

                self::$instance
                    ->query('SET character_set_results=utf8');

            } catch(PDOException $error) {
                echo $error->getMessage();
            }
        }

        return self::$instance;
    }

}
