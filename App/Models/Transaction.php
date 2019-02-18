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


}
