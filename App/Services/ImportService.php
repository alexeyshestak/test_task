<?php

namespace App\Services;

use App\DTO\ReportFields;
use App\Models\Batch;
use App\Models\CardType;
use App\Models\Merchant;
use App\Models\Transaction;
use App\Models\TransactionType;
use Classes\DB;
use Interfaces\Services\FileServiceInterface;
use Interfaces\Services\ImportServiceInterface;
use \Exception;

class ImportService implements ImportServiceInterface
{

    /** @var FileServiceInterface $fileService */
    protected $fileService;

    /** @var Batch $batch */
    protected $batch;

    /** @var CardType $cardType */
    protected $cardType;

    /** @var Merchant $merchant */
    protected $merchant;

    /** @var Transaction $transaction */
    protected $transaction;

    /** @var TransactionType $transactionType */
    protected $transactionType;

    /** @var ReportFields $bufferRow */
    protected $bufferRow;

    /**
     * Model constructor
     *
     * @param FileServiceInterface  $fileService        File url
     */
    public function __construct(FileServiceInterface $fileService)
    {
        $this->fileService = $fileService;

        // the better way it's to use DI to initiates models' instances
        $this->batch = new Batch();
        $this->cardType = new CardType();
        $this->merchant = new Merchant();
        $this->transaction = new Transaction();
        $this->transactionType = new TransactionType();

        $this->bufferRow = null;
    }

    /**
     * Imports rows to database
     *
     * @return void
     */
    public function import(): void
    {
        while ($rows = $this->getRows()) {
            $this->insertRows($rows);
        }
    }

    /**
     * Gets rows from to import (by batch)
     *
     * @return array
     */
    private function getRows(): array
    {
        $data = [];

        /** @var ReportFields $newRow */
        $newRow = $this->bufferRow ?? $this->fileService->getRow();

        if ($newRow) {
            do {
                $row = $newRow;

                $data[] = $row;

                $isSameBatch = false;
                $newRow = $this->fileService
                    ->getRow();

                if ($newRow) {
                    $isSameBatch = $newRow->getBatchDate() == $row->getBatchDate() &&
                        $newRow->getBatchNumber() == $row->getBatchNumber();
                }

            } while ($newRow && $isSameBatch);

            $this->bufferRow = $newRow;
        }

        return $data;
    }

    /**
     * Inserts rows
     *
     * @param array     $data   Array of ReportFields items
     */
    private function insertRows(array $data)
    {
        try {
            DB::beginTransaction();

            /** @var ReportFields $row */
            foreach ($data as $row) {
                $batch = $this->batch
                    ->getOrCreate(
                        $row->getBatchFields()
                    );

                $merchant = $this->merchant
                    ->getOrCreate(
                        $row->getMerchantFields()
                    );

                $cardType = $this->cardType
                    ->getOrCreate(
                        $row->getCardTypeFields()
                    );

                $transactionType = $this->transactionType
                    ->getOrCreate(
                        $row->getTransactionTypeFields()
                    );

                $transactionData = $row->getTransactionFields();
                $transactionData[Transaction::MERCHANT_ID]  = $merchant['mid'];
                $transactionData[Transaction::BATCH_ID]     = $batch['bid'];
                $transactionData[Transaction::CARD_TYPE_ID] = $cardType['id'];
                $transactionData[Transaction::TYPE_ID]      = $transactionType['id'];

                $transaction = $this->transaction
                    ->getOrCreate(
                        $transactionData
                    );

            }

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();

            throw new Exception($ex->getMessage());
        }
    }

}
