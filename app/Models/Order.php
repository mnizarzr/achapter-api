<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $table = "tbl_order";

    protected $fillable = ["shipper_id", "shipping_address"];

    protected $casts = ["completed" => 'boolean'];

    public function orderDetail()
    {
        return $this->hasMany('App\Models\OrderDetail');
    }

}