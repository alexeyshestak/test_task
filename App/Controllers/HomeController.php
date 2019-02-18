<?php

namespace App\Controllers;

use App\DTO\ReportFields;
use App\Models\Transaction;
use App\Services\FileService;
use Classes\Storage;
use Core\Response;

class HomeController
{

    /** @var string $fileLink */
    private $fileLink = './Resources/Files/report.csv';

    /** @var array $mapping */
    private $mapping = [
        ReportFields::TRANSACTION_DATE        => 'Transaction Date',
        ReportFields::TRANSACTION_TYPE        => 'Transaction Type',
        ReportFields::TRANSACTION_CARD_TYPE   => 'Transaction Card Type',
        ReportFields::TRANSACTION_CARD_NUMBER => 'Transaction Card Number',
        ReportFields::TRANSACTOIN_AMOUNT      => 'Transaction Amount',
        ReportFields::BATCH_DATE              => 'Batch Date',
        ReportFields::BATCH_REF_NUM           => 'Batch Reference Number',
        ReportFields::MERCHANT_ID             => 'Merchant ID',
        ReportFields::MERCHANT_NAME           => 'Merchant Name',
    ];

    /**
     * Returns text
     */
    public function index()
    {
        // TODO: implement services to get a csv-file, validate and import

        $fileService = new FileService($this->fileLink, $this->mapping);

        while ($response = $fileService->getRow()) {
            var_dump('----------------------------');
            var_dump($response->getMerchantFields());
            var_dump($response->getBatchFields());
            var_dump($response->getTransactionTypeFields());
            var_dump($response->getCardTypeFields());
            var_dump($response->getTransactionFields());
        }


        //$model = new Transaction();
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
