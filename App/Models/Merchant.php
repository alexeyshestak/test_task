<?php

namespace App\Models;

use Classes\DB;
use System\Model;

class Merchant extends Model
{

    /** @var string $tableName */
    protected $tableName = 'merchant';

    /**
     * Model constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


}
