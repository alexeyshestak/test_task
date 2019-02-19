<?php

return [
    'db_driver'     => 'mysql',

    'dsn'           => [
        'host'   => 'mysql',
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
        'PDO::ATTR_DEFAULT_FETCH_MODE' => 'PDO::FETCH_ASSOC',
    ],

    'charset'       => 'UTF-8',
];
