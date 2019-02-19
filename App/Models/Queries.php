<?php

namespace App\Models;

use Classes\DB;
use System\Model;
use \PDO;

class Queries extends Model
{

    /**
     * Model constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets all transactions for a batch (merchant + date + ref num)
     *
     * @param string    $batchDate      Batch date
     * @param string    $batchRefNnum   Batch reference number
     * @param string    $merchantID     Merchant identificator
     *
     * @return array
     */
    public function getAllTransForBatch(
        string $batchDate,
        string $batchRefNnum,
        string $merchantID
    ): array
    {
        $result = [];

        $statement = sprintf(
                        "SELECT
                            t.date,
                            tt.type,
                            ct.code AS card_type,
                            t.card_num AS card_number,
                            t.amount
                        FROM transaction AS t
                            INNER JOIN transaction_type AS tt ON tt.id = t.type_id
                            INNER JOIN card_type AS ct ON ct.id = t.card_type_id
                            INNER JOIN batch AS b ON b.bid = t.batch_id
                                AND b.batch_date = '%s'
                                AND b.batch_ref_num = '%s'
                            INNER JOIN merchant AS m ON m.mid = t.merchant_id
                                AND m.mid = '%s'",
                        $batchDate,
                        $batchRefNnum,
                        $merchantID
                    );

        $query = DB::prepare($statement);
        $query->execute();

        if ($query->rowCount()) {
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

    /**
     * Display stats for a batch (per card type)
     *
     * @param string    $batchDate      Batch date
     * @param string    $batchRefNnum   Batch reference number
     *
     * @return array
     */
    public function getStatsForBatch(
        string $batchDate,
        string $batchRefNnum
    ): array
    {
        $result = [];

        $statement = sprintf(
                        "SELECT
                            b.batch_date,
                            b.batch_ref_num,
                            ct.code AS card_type,
                            COUNT(t.amount) AS transactions,
                            SUM(t.amount) AS amount
                        FROM transaction AS t
                            INNER JOIN transaction_type AS tt ON tt.id = t.type_id
                            INNER JOIN card_type AS ct ON ct.id = t.card_type_id
                            INNER JOIN batch AS b ON b.bid = t.batch_id
                                AND b.batch_date = '%s'
                                AND b.batch_ref_num = '%s'
                        GROUP BY
                            b.batch_date,
                            b.batch_ref_num,
                            ct.code
                        ORDER BY
                            b.batch_date,
                            b.batch_ref_num",
                        $batchDate,
                        $batchRefNnum
                    );

        $query = DB::prepare($statement);
        $query->execute();

        if ($query->rowCount()) {
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

    /**
     * Display stats for a merchant and a given date range
     *
     * @param string    $merchantID     Merchant identificator
     * @param string    $dateStart      Date start
     * @param string    $dateEnd        Date end
     *
     * @return array
     */
    public function getStatsForMerchant(
        string $merchantID,
        string $dateStart,
        string $dateEnd
    ): array
    {
        $result = [];

        $statement = sprintf(
                        "SELECT
                            m.mid AS merchant_id,
                            m.dba AS merchant_name,
                            ct.code AS card_type,
                            COUNT(t.amount) AS transactions,
                            SUM(t.amount) AS amount
                        FROM transaction AS t
                            INNER JOIN transaction_type AS tt ON tt.id = t.type_id
                            INNER JOIN card_type AS ct ON ct.id = t.card_type_id
                            INNER JOIN merchant AS m ON m.mid = t.merchant_id
                                AND m.mid = '%s'
                        WHERE t.date BETWEEN '%s' AND '%s'
                        GROUP BY
                            m.mid,
                            m.dba,
                            ct.code
                        ORDER BY
                            m.mid,
                            m.dba",
                        $merchantID,
                        $dateStart,
                        $dateEnd
                    );

        $query = DB::prepare($statement);
        $query->execute();

        if ($query->rowCount()) {
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

    /**
     * Display top 10 merchants
     *
     * @param string    $dateStart      Date start
     * @param string    $dateEnd        Date end
     *
     * @return array
     */
    public function getTopMerchants(
        string $dateStart,
        string $dateEnd
    ): array
    {
        $result = [];

        $statement = sprintf(
                        "SELECT
                            m.mid AS merchant_id,
                            m.dba AS merchant_name,
                            COUNT(t.amount) AS transactions,
                            SUM(t.amount) AS amount
                        FROM transaction AS t
                            INNER JOIN transaction_type AS tt ON tt.id = t.type_id
                            INNER JOIN merchant AS m ON m.mid = t.merchant_id
                        WHERE t.date BETWEEN '%s' AND '%s'
                        GROUP BY
                            m.mid,
                            m.dba
                        ORDER BY amount DESC
                        LIMIT 10",
                        $dateStart,
                        $dateEnd
                    );

        $query = DB::prepare($statement);
        $query->execute();

        if ($query->rowCount()) {
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

}
