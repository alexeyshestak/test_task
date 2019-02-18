<?php

namespace App\Controllers;

use Classes\DB;
use Core\Response;

class Query3Controller
{

    /**
     * Returns text
     */
    public function index()
    {
        $response = [
            '<a href="/query1/">All transactions for a batch (merchant + date + ref num)</a><br />',
            '<a href="/query2/">Display stats for a batch (per card type)</a><br />',
            '<a href="/query4/">Display top 10 merchants (by total amount) for a given date range</a><br />',
            '-------------------------<br />',
            'Display stats for a merchant and a given date range<br />',
        ];

        $result = [];

        $statement = "SELECT
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
                            m.dba";

        $query = DB::prepare($statement);
        $query->execute();

        if ($query->rowCount()) {
            $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        }

        $response[] = $result;

        Response::render($response);
    }

}
