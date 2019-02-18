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

        $type = $type ?? $_GET['output'] ?? null;
        $output = $type ?? 'html';

        switch ($output) {
            case 'echo':
            case 'print':
            case 'json':
            case 'html':
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

    /**
     * Prints data
     *
     * @param mixed         $response
     */
    private static function html($response)
    {
        foreach ($response as $item) {
            switch (true) {
                case is_string($item):
                    echo $item;
                    break;
                case is_array($item):
                    echo '<table>';
                    $isFirst = true;
                    foreach ($item as $row) {
                        if ($isFirst) {
                            $isFirst = false;
                            echo '<tr>';
                            foreach ($row as $key => $value) {
                                echo '<th>' . $key . '</th>';
                            }
                            echo '</tr>';
                        }
                        echo '<tr>';
                        foreach ($row as $key => $value) {
                            echo '<td>' . $value . '</td>';
                        }
                        echo '</tr>';
                    }
                    echo '</table>';
                    break;
                default:
                    self::print($item);
                    break;
            }
        }
    }

}
