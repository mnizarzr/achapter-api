<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

class Order extends Model
{

    protected $table = "tbl_order";

    protected $fillable = ["shipper_id", "shipping_address"];

    protected $casts = ["completed" => 'boolean'];

    public $timestamps = false;

    public function orderDetail()
    {
        return $this->hasMany('App\Models\OrderDetail');
    }

    public function payment()
    {
        return $this->hasOne('App\Models\Payment');
    }

}