<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model {

    protected $table = 'tbl_author';

    protected $fillable = ['name', 'biography'];

    public function books()
    {
        return $this->belongsToMany("App\Models\Book", "tbl_book_author", "author_id", "book_id");
    }

}

?>