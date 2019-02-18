<?php

namespace App\Controllers;

use Classes\DB;
use Core\Response;

class Query1Controller
{

    /**
     * Returns text
     */
    public function index()
    {
        $response = [
            '<a href="/query2/">Display stats for a batch (per card type)</a><br />',
            '<a href="/query3/">Display stats for a merchant and a given date range</a><br />',
            '<a href="/query4/">Display top 10 merchants (by total amount) for a given date range</a><br />',
            '-------------------------<br />',
            'All transactions for a batch (merchant + date + ref num)<br />',
        ];

        $result = [];

        $statement = "SELECT
                            t.date,
                            tt.type,
                            ct.code AS card_type,
                            t.card_num AS card_number,
                            t.amount
                        FROM transaction AS t
                            INNER JOIN transaction_type AS tt ON tt.id = t.type_id
                            INNER JOIN card_type AS ct ON ct.id = t.card_type_id
                            INNER JOIN batch AS b ON b.bid = t.batch_id
                                AND b.batch_date = '2018-05-05'
                                AND b.batch_ref_num = '219022643874254207378'
                            INNER JOIN merchant AS m ON m.mid = t.merchant_id
                                AND m.mid = '5241045810727730'";

        $query = DB::prepare($statement);
        $query->execute();

        if ($query->rowCount()) {
            $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        }

        $response[] = $result;

        Response::render($response);
    }

}
