<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookDetail extends Model{

    protected $primaryKey = "book_id";

    protected $table = "tbl_book_detail";

    protected $fillable = ["*"];

    public $timestamps = false;

    public function book(){
        return $this->belongsTo("App\Models\Book");
    }

}

?>