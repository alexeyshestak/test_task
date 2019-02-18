<?php

namespace App\Controllers;

use App\DTO\ReportFields;
use App\Models\Batch;
use App\Models\CardType;
use App\Models\Merchant;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Services\FileService;
use App\Services\ImportService;
use Core\Response;
use \Exception;

class HomeController
{

    /** @var string $fileLink */
    //private $fileLink = './Resources/Files/error_report.csv';
    //private $fileLink = './Resources/Files/1report.csv';
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
        $response = 'File has been imported';

        try {
            $fileService = new FileService($this->fileLink, $this->mapping);

            $importService = new ImportService(
                $fileService,
                new Batch(),
                new CardType(),
                new Merchant(),
                new Transaction(),
                new TransactionType()
            );

            $importService->import();

        } catch (Exception $ex) {
            Response::render($ex->getMessage());
        }

        Response::render($response);
    }

}
