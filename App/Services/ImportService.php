<?php

namespace App\Services;

use App\DTO\ReportFields;
use App\Models\Batch;
use App\Models\CardType;
use App\Models\Merchant;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Services\FileService;
use Classes\DB;
use \Exception;

class ImportService
{

    /** @var FileService $fileService */
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

    /**
     * Model constructor
     *
     * @param FileService       $fileService        File url
     * @param Batch             $batch              Batch model instance
     * @param CardType          $cardType           CardType model instance
     * @param Merchant          $merchant           Merchant model instance
     * @param Transaction       $transaction        Transaction model instance
     * @param TransactionType   $transactionType    TransactionType model instance
     */
    public function __construct(
        FileService $fileService,
        Batch $batch,
        CardType $cardType,
        Merchant $merchant,
        Transaction $transaction,
        TransactionType $transactionType
    ) {
        $this->fileService = $fileService;

        $this->batch = $batch;
        $this->cardType = $cardType;
        $this->merchant = $merchant;
        $this->transaction = $transaction;
        $this->transactionType = $transactionType;
    }

    /**
     * Imports rows to database
     */
    public function import()
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
        $newRow = $this->fileService
            ->getRow();

        if ($newRow) {
            do {
                $row = $newRow;
                $data[] = $row;

                $isSameBatch = false;
                $newRow = $this->fileService
                    ->getRow();

                if ($newRow) {
                    $isSameBatch = $newRow->getBatchDate() === $row->getBatchDate() &&
                        $newRow->getBatchNumber() === $row->getBatchNumber();
                }

            } while ($newRow && $isSameBatch);
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
                $transactionData[Transaction::MERCHANT_ID] = $merchant['mid'];
                $transactionData[Transaction::BATCH_ID] = $batch['bid'];
                $transactionData[Transaction::CARD_TYPE_ID] = $cardType['id'];
                $transactionData[Transaction::TYPE_ID] = $transactionType['id'];

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
