<?php

namespace App\Models;

use Classes\DB;
use System\Model;

class Batch extends Model
{

    /** @var string $tableName */
    protected $tableName = 'batch';

    /**
     * Model constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


}
