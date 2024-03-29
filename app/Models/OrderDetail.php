<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{

    protected $table = "tbl_order_detail";

    protected $guarded = ['id', 'order_id'];

    public $timestamps = false;

    public function order()
    {
        return $this->belongsTo("App\Models\Order");
    }

}
