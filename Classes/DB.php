<?php

namespace Classes;

use \PDO;

class DB
{

    /** @var PDO Instance */
    private static $instance;

    /**
     * Static calls
     *
     * @param  mixed    $name   Function name of call
     * @param  mixed    $args   Arguments of function
     *
     * @return mixed
     */
    public static function __callStatic($name, $args)
    {
        $callback = array(
            self::getInstance(),
            $name
        );

        return call_user_func_array($callback, $args);
    }

    /**
     * Get DB instance
     *
     * @return PDO
     */
    private static function getInstance()
    {
        if (self::$instance) {
            return self::$instance ;
        }

        $dbConfig = config('db');

        $driver = $dbConfig['db_driver'];
        $dsn = "${driver}:";
        $user = $dbConfig['db_user'];

        $password = $dbConfig['db_password'];
        $options = $dbConfig['db_options'];
        $attributes = $dbConfig['db_attributes'];

        foreach ($dbConfig['dsn'] as $k => $v) {
            $dsn .= "${k}=${v};" ;
        }

        self::$instance = new PDO($dsn, $user, $password, $options);

        foreach ($attributes as $k => $v) {
            self::$instance
                ->setAttribute(
                    constant("PDO::{$k}"),
                    constant ("PDO::{$v}")
                );
        }

        return self::$instance;
    }

}
