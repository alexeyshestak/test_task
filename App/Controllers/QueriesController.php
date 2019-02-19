<?php

namespace App\Controllers;

use App\Models\Queries;
use Core\Response;

class QueriesController
{

    /** @var Queries $model */
    private $model;

    /**
     * Controller constructor
     */
    public function __construct()
    {
        $this->model = new Queries();
    }

    /**
     * Returns text
     */
    public function getAllTransForBatch()
    {
        $params = config('settings')['query1'];

        $result = $this->model
            ->getAllTransForBatch(
                $params['batchDate'],
                $params['batchRefNum'],
                $params['merchantID']
            );

        $response = [
            '<a href="/queries/getStatsForBatch">Display stats for a batch (per card type)</a><br />',
            '<a href="/queries/getStatsForMerchant">Display stats for a merchant and a given date range</a><br />',
            '<a href="/queries/getTopMerchants">Display top 10 merchants (by total amount) for a given date range</a><br />',
            '-------------------------<br />',
            'All transactions for a batch (merchant + date + ref num)<br />',
            $result,
        ];

        Response::render($response);
    }

    /**
     * Returns text
     */
    public function getStatsForBatch()
    {
        $params = config('settings')['query2'];

        $result = $this->model
            ->getStatsForBatch(
                $params['batchDate'],
                $params['batchRefNum']
            );

        $response = [
            '<a href="/queries/getAllTransForBatch">All transactions for a batch (merchant + date + ref num)</a><br />',
            '<a href="/queries/getStatsForMerchant">Display stats for a merchant and a given date range</a><br />',
            '<a href="/queries/getTopMerchants">Display top 10 merchants (by total amount) for a given date range</a><br />',
            '-------------------------<br />',
            'Display stats for a batch (per card type)<br />',
            $result,
        ];

        Response::render($response);
    }

    /**
     * Returns text
     */
    public function getStatsForMerchant()
    {
        $params = config('settings')['query3'];

        $result = $this->model
            ->getStatsForMerchant(
                $params['merchantID'],
                $params['dateStart'],
                $params['dateEnd']
            );

        $response = [
            '<a href="/queries/getAllTransForBatch">All transactions for a batch (merchant + date + ref num)</a><br />',
            '<a href="/queries/getStatsForBatch">Display stats for a batch (per card type)</a><br />',
            '<a href="/queries/getTopMerchants">Display top 10 merchants (by total amount) for a given date range</a><br />',
            '-------------------------<br />',
            'Display stats for a merchant and a given date range<br />',
            $result,
        ];

        Response::render($response);
    }

    /**
     * Returns text
     */
    public function getTopMerchants()
    {
        $params = config('settings')['query4'];

        $result = $this->model
            ->getTopMerchants(
                $params['dateStart'],
                $params['dateEnd']
            );

        $response = [
            '<a href="/queries/getAllTransForBatch">All transactions for a batch (merchant + date + ref num)</a><br />',
            '<a href="/queries/getStatsForBatch">Display stats for a batch (per card type)</a><br />',
            '<a href="/queries/getStatsForMerchant">Display stats for a merchant and a given date range</a><br />',
            '-------------------------<br />',
            'Display top 10 merchants (by total amount) for a given date range<br />',
            $result,
        ];

        Response::render($response);
    }

}
