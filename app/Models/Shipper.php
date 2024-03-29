<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipper extends Model
{
    protected $table = "tbl_shipper";

    protected $fillable = ["name"];

    public function orders()
    {
        return $this->hasMany("App\Models\Order");
    }

}