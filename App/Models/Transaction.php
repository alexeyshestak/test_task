<?php

namespace App\Models;

use Classes\DB;
use System\Model;

class Transaction extends Model
{

    /** @var string $tableName */
    protected $tableName = 'transaction';

    /**
     * Model constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


}
