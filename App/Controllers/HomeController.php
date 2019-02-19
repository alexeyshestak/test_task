<?php

namespace App\Controllers;

use App\Services\FileService;
use App\Services\ImportService;
use Core\Response;
use \Exception;

class HomeController
{

    /** @var string $fileLink */
    private $fileLink;

    /** @var array $mapping */
    private $mapping;

    /**
     * Controller constructor
     */
    public function __construct()
    {
        $settings = config('settings');

        $this->fileLink = $settings['fileLink'];
        $this->mapping = $settings['mapping'];
    }

    /**
     * Returns text
     */
    public function index()
    {
        $response = [
            'File has been imported<br />',
            '-------------------------<br />',
            '<a href="/queries/getAllTransForBatch">All transactions for a batch (merchant + date + ref num)</a><br />',
            '<a href="/queries/getStatsForBatch">Display stats for a batch (per card type)</a><br />',
            '<a href="/queries/getStatsForMerchant">Display stats for a merchant and a given date range</a><br />',
            '<a href="/queries/getTopMerchants">Display top 10 merchants (by total amount) for a given date range</a><br />',
        ];

        try {
            $fileService = new FileService($this->fileLink, $this->mapping);
            $importService = new ImportService($fileService);

            $importService->import();

        } catch (Exception $ex) {
            Response::render($ex->getMessage());
        }

        Response::render($response);
    }

}
