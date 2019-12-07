<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{


    protected $table = "tbl_book";

    protected $fillable = ["ISBN", "title", "publishing year"];

    protected $hidden = ['author_id', 'publisher_id'];

    public function bookDetail()
    {
        return $this->hasOne("App\Models\BookDetail");
    }

    /**
     *  Note: Use manual relationship
     *  Author field type is text/json;
     *  public function author(){
     *      return $this->belongsToMany("App\Models\Author", "tbl_author");
     *  }
     */
}
