<?php

namespace App\DTO;

class ReportFields
{

    const TRANSACTION_DATE        = 'date';

    const TRANSACTION_TYPE        = 'type';

    const TRANSACTION_CARD_TYPE   = 'code';

    const TRANSACTION_CARD_NUMBER = 'card_num';

    const TRANSACTOIN_AMOUNT      = 'amount';

    const BATCH_DATE              = 'batch_date';

    const BATCH_REF_NUM           = 'batch_ref_num';

    const MERCHANT_ID             = 'mid';

    const MERCHANT_NAME           = 'dba';

    /**
     * Sets and gets values
     *
     * @param  string   $method     Function name
     * @param  array    $params     Array of parameters
     *
     * @return mixed
     */
    function __call($method, $params)
    {
        $var = lcfirst(substr($method, 3));

         if (strncasecmp($method, 'get', 3) === 0) {
             return $this->$var;
         }

         if (strncasecmp($method, 'set', 3) === 0) {
             $this->$var = $params[0];
         }
    }

    /**
     * Returns array for merchant creation or search
     *
     * @return array
     */
    public function getMerchantFields(): array
    {
        $result = [
            self::MERCHANT_ID   => $this->{self::MERCHANT_ID},
            self::MERCHANT_NAME => $this->{self::MERCHANT_NAME},
        ];

        return $result;
    }

    /**
     * Returns array for batch creation or search
     *
     * @return array
     */
    public function getBatchFields(): array
    {
        $result = [
            self::BATCH_DATE    => $this->{self::BATCH_DATE},
            self::BATCH_REF_NUM => $this->{self::BATCH_REF_NUM},
        ];

        return $result;
    }

    /**
     * Returns array for transaction type creation or search
     *
     * @return array
     */
    public function getTransactionTypeFields(): array
    {
        $result = [
            self::TRANSACTION_TYPE => $this->{self::TRANSACTION_TYPE},
        ];

        return $result;
    }

    /**
     * Returns array for card type creation or search
     *
     * @return array
     */
    public function getCardTypeFields(): array
    {
        $result = [
            self::TRANSACTION_CARD_TYPE => $this->{self::TRANSACTION_CARD_TYPE},
        ];

        return $result;
    }

    /**
     * Returns array for transaction creation or search
     *
     * @return array
     */
    public function getTransactionFields(): array
    {
        $result = [
            self::TRANSACTION_DATE        => $this->{self::TRANSACTION_DATE},
            self::TRANSACTION_CARD_NUMBER => $this->{self::TRANSACTION_CARD_NUMBER},
            self::TRANSACTOIN_AMOUNT      => $this->{self::TRANSACTOIN_AMOUNT},
        ];

        return $result;
    }

    /**
     * Get batch date
     *
     * @return string|null
     */
    public function getBatchDate(): ?string
    {

        return property_exists($this, self::BATCH_DATE) ?
            $this->{self::BATCH_DATE} :
            null;
    }

    /**
     * Get batch number
     *
     * @return string|null
     */
    public function getBatchNumber(): ?string
    {

        return property_exists($this, self::BATCH_REF_NUM) ?
            $this->{self::BATCH_REF_NUM} :
            null;
    }

}
