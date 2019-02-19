<?php

namespace App\DTO;

use App\DTO\ReportFields;

class InsertionData
{

    const MERCHANT     = 'merchant';

    const TYPE         = 'type';

    const CARD_TYPE    = 'code';

    const TRANSACTION  = 'transaction';

    const TRANSACTIONS = 'transactions';

    /** @var array $batch */
    private $batch = [];

    /** @var array $merchants */
    private $merchants = [];

    /**
     * Get batch info
     *
     * @return array|null
     */
    public function getBatchFields(): ?array
    {
        return $this->batch;
    }

    /**
     * Set batch info
     *
     * @param array     $batch      Batch info array
     *
     * @return InsertionData
     */
    public function setBatchFields(array $batch): InsertionData
    {
        $this->batch = $batch;

        return $this;
    }

    /**
     * Get transactions info
     *
     * @return array|null
     */
    public function getTransactions(): ?array
    {
        return $this->merchants;
    }

    /**
     * Add transaction info
     *
     * @param array     $merchant           Merchant info array
     * @param array     $cardType           Card type info array
     * @param array     $transactionType    Transaction type info array
     * @param array     $transaction        Transaction info array
     *
     * @return InsertionData
     */
    public function addTransaction(
        array $merchant,
        array $cardType,
        array $transactionType,
        array $transaction
    ): InsertionData
    {
        $transactionData = [
            self::TYPE        => $transactionType,
            self::CARD_TYPE   => $cardType,
            self::TRANSACTION => $transaction,
        ];

        $isNotFound = true;

        foreach ($this->merchants as $key => $item) {
            if ($item[self::MERCHANT][ReportFields::MERCHANT_ID] == $merchant[ReportFields::MERCHANT_ID]) {
                $item[self::TRANSACTIONS][] = $transactionData;
                $this->merchants[$key] = $item;
                $isNotFound = false;
            }
        }

        if ($isNotFound) {
            $findItem = [
                self::MERCHANT     => $merchant,
                self::TRANSACTIONS => [$transactionData, ],
            ];

            $this->merchants[] = $findItem;
        }

        return $this;
    }

}
