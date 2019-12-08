<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{

    protected $increments = true;

    protected $table = "tbl_book";

    protected $fillable = ["ISBN", "title", "publishing_date"];

    protected $hidden = ['author_id', 'publisher_id'];

    public function bookDetail()
    {
        return $this->hasOne("App\Models\BookDetail");
    }

    public function publisher()
    {
        return $this->belongsTo("App\Models\Publisher");
    }

    public function authors()
    {

        return $this->belongsToMany("App\Models\Author", "tbl_book_author", "book_id", "author_id");
    }

    public function genres()
    {

        return $this->belongsToMany("App\Models\Genre", "tbl_book_genre", "book_id", "genre_id");
    }


}
