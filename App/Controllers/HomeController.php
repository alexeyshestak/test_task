<?php

namespace App\Controllers;

use App\Models\Transaction;
use Classes\Storage;
use Core\Response;

class HomeController
{

    /**
     * Returns text
     */
    public static function index()
    {
        $model = new Transaction();

        // TODO: implement services to get a csv-file, validate and import

        $fileLink = './Resources/Files/report.csv';

        $file = Storage::getFile($fileLink);

        var_dump($file);
        fclose($file);

        //$response = $model->getAll();
        /*$response = $model->getOrCreate([
            'merchant_id'  => '1',
            'batch_id'     => '1',
            'date'         => '2019-02-19',
            'type_id'      => '1',
            'card_type_id' => '1',
            'card_num'     => '2',
            'amount'       => '2',
        ]);*/

        Response::render($response);
    }

}
