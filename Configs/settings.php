<?php

use App\DTO\ReportFields;

return [
    'fileLink' => './Resources/Files/report.csv',

    'mapping'  => [
        ReportFields::TRANSACTION_DATE        => 'Transaction Date',
        ReportFields::TRANSACTION_TYPE        => 'Transaction Type',
        ReportFields::TRANSACTION_CARD_TYPE   => 'Transaction Card Type',
        ReportFields::TRANSACTION_CARD_NUMBER => 'Transaction Card Number',
        ReportFields::TRANSACTOIN_AMOUNT      => 'Transaction Amount',
        ReportFields::BATCH_DATE              => 'Batch Date',
        ReportFields::BATCH_REF_NUM           => 'Batch Reference Number',
        ReportFields::MERCHANT_ID             => 'Merchant ID',
        ReportFields::MERCHANT_NAME           => 'Merchant Name',
    ],

    'query1'   => [
        'batchDate'   => '2018-05-05',
        'batchRefNum' => '219022643874254207378',
        'merchantID'  => '5241045810727730',
    ],

    'query2'   => [
        'batchDate'   => '2018-05-05',
        'batchRefNum' => '219022643874254207378',
    ],

    'query3'   => [
        'merchantID'  => '2264135688721936',
        'dateStart'   => '2018-05-04',
        'dateEnd'     => '2018-05-05',
    ],

    'query4'   => [
        'dateStart'   => '2018-05-04',
        'dateEnd'     => '2018-05-05',
    ],
];
