<?php

return [
    'db_driver'     => 'mysql',

    'dsn'           => [
        'host'   => 'mysql',
        //'host'   => 'localhost',
        'port'   => '3306',
        'dbname' => 'test.loc',
    ],

    'db_user'       => 'root',
    'db_password'   => 'root',

    'db_attributes' => [
        'ATTR_ERRMODE' => 'ERRMODE_EXCEPTION',
    ],

    'db_options'    => [
        'PDO::MYSQL_ATTR_INIT_COMMAND' => 'set names utf8',
    ],

    'charset'       => 'UTF-8',
];
