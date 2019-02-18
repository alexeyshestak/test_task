<?php

namespace Core;

class Response
{

    /**
     * Echo data
     *
     * @param mixed         $response
     * @param string|null   $type
     */
    public static function render($response, $type = null)
    {

        $type = $type ?? $_GET['output'];
        $output = $type ?? 'print';

        switch ($output) {
            case 'echo':
            case 'print':
            case 'json':
                self::{$output}($response);
                break;
            default:
                self::json($response);
                break;
        }
    }

    /**
     * Prints json data
     *
     * @param mixed         $response
     */
    private static function json($response)
    {
        echo json_encode($response);
    }

    /**
     * Echo data
     *
     * @param mixed         $response
     */
    private static function echo($response)
    {
        echo $response;
    }

    /**
     * Prints data
     *
     * @param mixed         $response
     */
    private static function print($response)
    {
        echo '<pre>';
        print_r($response);
        echo '</pre>';
    }

}
