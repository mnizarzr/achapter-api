<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    protected $table = "tbl_payment";

    public $timestamps = false;

    public function order()
    {
        return $this->belongsTo("App\Models\Order");
    }


}

?>