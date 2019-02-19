<?php

namespace App\Services;

use App\DTO\InsertionData;
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
     * @return InsertionData
     */
    private function getRows(): InsertionData
    {
        $data = new InsertionData();

        /** @var ReportFields $newRow */
        $newRow = $this->bufferRow ?? $this->fileService->getRow();

        if ($newRow) {
            do {
                $row = $newRow;

                $data->setBatchFields($row->getBatchFields())
                    ->addTransaction(
                        $row->getMerchantFields(),
                        $row->getCardTypeFields(),
                        $row->getTransactionTypeFields(),
                        $row->getTransactionFields()
                    );

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
     * @param InsertionData     $data   Structure of insertion items
     */
    private function insertRows(InsertionData $data)
    {
        try {
            DB::beginTransaction();

            $toInsert = [];

            $batch = $this->batch
                ->getOrCreate(
                    $data->getBatchFields()
                );

            $merchants = $data->getTransactions();
            foreach ($merchants as $item) {
                $merchant = $this->merchant
                    ->getOrCreate(
                        $item[InsertionData::MERCHANT]
                    );

                $transactions = $item[InsertionData::TRANSACTIONS];
                foreach ($transactions as $transaction) {
                    $cardType = $this->cardType
                        ->getOrCreate(
                            $transaction[InsertionData::CARD_TYPE]
                        );

                    $transactionType = $this->transactionType
                        ->getOrCreate(
                            $transaction[InsertionData::TYPE]
                        );

                    $transactionData = $transaction[InsertionData::TRANSACTION];
                    $transactionData[Transaction::MERCHANT_ID]  = $merchant['mid'];
                    $transactionData[Transaction::BATCH_ID]     = $batch['bid'];
                    $transactionData[Transaction::CARD_TYPE_ID] = $cardType['id'];
                    $transactionData[Transaction::TYPE_ID]      = $transactionType['id'];

                    $transaction = $this->transaction
                        ->getFirst(
                            $transactionData
                        );

                    if (empty($transaction)) {
                        $toInsert[] = $transactionData;
                    }
                }

            }

            $this->transaction
                ->groupInsert($toInsert);

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();

            throw new Exception($ex->getMessage());
        }
    }

}
