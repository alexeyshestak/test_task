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

    /*

    //display all transactions for a batch (merchant + date + ref num)
    //date, type, card_type, card_number, amount

    SELECT
        t.date,
        tt.type,
        ct.code AS card_type,
        t.card_num AS card_number,
        t.amount
    FROM transaction AS t
        INNER JOIN transaction_type AS tt ON tt.id = t.type_id
        INNER JOIN card_type AS ct ON ct.id = t.card_type_id
        INNER JOIN batch AS b ON b.bid = t.batch_id
            AND b.batch_date = '2018-05-05'
            AND b.batch_ref_num = '219022643874254207378'
        INNER JOIN merchant AS m ON m.mid = t.merchant_id
            AND m.mid = '5241045810727730'

    ---------------------------------------------------------------------

    //display stats for a batch
    //per card type (VI - 2 transactions with $100 total, MC - 10 transaction with $200 total)

    SELECT
        b.batch_date,
        b.batch_ref_num,
        ct.code AS card_type,
        COUNT(t.amount) AS transactions,
        SUM(t.amount) AS amount
    FROM transaction AS t
        INNER JOIN transaction_type AS tt ON tt.id = t.type_id
        INNER JOIN card_type AS ct ON ct.id = t.card_type_id
        INNER JOIN batch AS b ON b.bid = t.batch_id
    GROUP BY
        b.batch_date,
        b.batch_ref_num,
        ct.code
    ORDER BY
        b.batch_date,
        b.batch_ref_num

    ---------------------------------------------------------------------

    //display stats for a merchant and a given date range

    SELECT
        m.mid,
        m.dba,
        ct.code AS card_type,
        COUNT(t.amount) AS transactions,
        SUM(t.amount) AS amount
    FROM transaction AS t
        INNER JOIN transaction_type AS tt ON tt.id = t.type_id
        INNER JOIN card_type AS ct ON ct.id = t.card_type_id
        INNER JOIN merchant AS m ON m.mid = t.merchant_id
    WHERE t.date BETWEEN '2018-05-04' AND '2018-05-05'
    GROUP BY
        m.mid,
        m.dba,
        ct.code
    ORDER BY
        m.mid,
        m.dba

    ---------------------------------------------------------------------

    //display top 10 merchants (by total amount) for a given date range
    //merchant id, merchant name, total amount, number of transactions

    SELECT
        m.mid,
        m.dba,
        COUNT(t.amount) AS transactions,
        SUM(t.amount) AS amount
    FROM transaction AS t
        INNER JOIN transaction_type AS tt ON tt.id = t.type_id
        INNER JOIN merchant AS m ON m.mid = t.merchant_id
    WHERE t.date BETWEEN '2018-05-04' AND '2018-05-05'
    GROUP BY
        m.mid,
        m.dba
    ORDER BY amount DESC
    LIMIT 10

     */

}
