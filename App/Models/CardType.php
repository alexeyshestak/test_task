<?php

namespace App\Models;

use Classes\DB;
use System\Model;

class CardType extends Model
{

    /** @var string $tableName */
    protected $tableName = 'card_type';

    /**
     * Model constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


}
