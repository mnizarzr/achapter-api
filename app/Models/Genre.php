<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model {

    protected $table = 'tbl_genre';

    protected $fillable = ['name'];

    public function books()
    {
        return $this->belongsToMany("App\Models\Book", "tbl_book_genre", "genre_id", "book_id");
    }

}

?>