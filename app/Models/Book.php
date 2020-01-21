<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Book extends Model
{

    protected $increments = true;

    protected $table = "tbl_book";

    protected $fillable = ["*"];

    protected $hidden = ['author_id', 'publisher_id'];

    public function setCreatedByAttribute() {
        $this->attributes['created_by'] = Auth::user()["id"];
    }

    public function setUpdatedByAttribute() {
        $this->attributes['updated_by'] = Auth::user()["id"];
    }

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
