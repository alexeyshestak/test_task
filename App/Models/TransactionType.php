<?php

namespace App\Models;

use Classes\DB;
use System\Model;

class TransactionType extends Model
{

    /** @var string $tableName */
    protected $tableName = 'transaction_type';

    /**
     * Model constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


}
