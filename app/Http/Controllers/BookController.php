<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookDetail;
use Illuminate\Http\Request;
use Symfony\Component\Console\Helper\Helper;

class BookController extends Controller
{

    public function index(Request $request)
    {

        $book = new Book();
        $bookDetail = new BookDetail();

        $book->ISBN = $request->ISBN;
        $book->title = $request->title;
        $book->author_id = $request->authorId;

        $bookDetail->price = $request->price;
        $bookDetail->stock = $request->stock;

        $book->save();
        $book->bookDetail()->save($bookDetail);

        return response()->json([
            "status" => 201,
            "error" => null,
            "message" => "Book added",
            "data" => array_merge($book->toArray(), $bookDetail->toArray())
        ], 201);
    }


    public function find($id)
    {

        $book = Book::find($id);

        if ($book == null) return response()->json([
            "status" => 404,
            "error" => "ERR_NOT_FOUND",
            "message" => "Not found"
        ], 404);

        $author = Author::find($book->author_id);
        $book["authors"] = $author->toArray();

        return response()->json([
            "status" => 200,
            "error" => null,
            "message" => "{$book->title} found",
            "data" => $book
        ], 200);
    }

}
