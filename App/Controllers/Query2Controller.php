<?php

namespace App\Controllers;

use Classes\DB;
use Core\Response;

class Query2Controller
{

    /**
     * Returns text
     */
    public function index()
    {
        $response = [
            '<a href="/query1/">All transactions for a batch (merchant + date + ref num)</a><br />',
            '<a href="/query3/">Display stats for a merchant and a given date range</a><br />',
            '<a href="/query4/">Display top 10 merchants (by total amount) for a given date range</a><br />',
            '-------------------------<br />',
            'Display stats for a batch (per card type)<br />',
        ];

        $result = [];

        $statement = "SELECT
                            b.batch_date,
                            b.batch_ref_num,
                            ct.code AS card_type,
                            COUNT(t.amount) AS transactions,
                            SUM(t.amount) AS amount
                        FROM transaction AS t
                            INNER JOIN transaction_type AS tt ON tt.id = t.type_id
                            INNER JOIN card_type AS ct ON ct.id = t.card_type_id
                            INNER JOIN batch AS b ON b.bid = t.batch_id
                        GROUP BY
                            b.batch_date,
                            b.batch_ref_num,
                            ct.code
                        ORDER BY
                            b.batch_date,
                            b.batch_ref_num";

        $query = DB::prepare($statement);
        $query->execute();

        if ($query->rowCount()) {
            $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        }

        $response[] = $result;

        Response::render($response);
    }

}
