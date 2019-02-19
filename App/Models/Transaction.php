<?php

namespace App\Models;

use Classes\DB;
use System\Model;

class Transaction extends Model
{

    const MERCHANT_ID  = 'merchant_id';

    const BATCH_ID     = 'batch_id';

    const TYPE_ID      = 'type_id';

    const CARD_TYPE_ID = 'card_type_id';

    /** @var string $tableName */
    protected $tableName = 'transaction';

    /**
     * Model constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Creates records by provided associated array of columns and values
     *
     * @param array     $data   Array of data
     *
     * @return bool
     */
    public function groupInsert(
        array $data
    ): bool
    {
        $result = false;

        if (!$this->tableName) {
            return $result;
        }

        if (count($data) >= 1) {
            $keys = array_keys($data[0]);
            $columns = '`' . implode('`, `', $keys) . '`';
            $values = '';

            $isFirst = true;
            foreach ($data as $item) {
                if ($isFirst) {
                    $isFirst = false;
                } else {
                    $values .= ', ';
                }

                $values .= '(' . join(', ', array_values($item)) . ')';
            }

            $statement = "INSERT INTO `$this->tableName` ($columns) VALUES ($values)";

            $query = DB::prepare($statement);
            $result = $query->execute();
        }

        return $result;
    }

}
