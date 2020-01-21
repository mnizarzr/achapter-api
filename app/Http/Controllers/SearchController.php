<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;

class SearchController extends Controller
{

    function __construct()
    {
        $this->middleware('hasJWT');
    }

    function searchResponse($data)
    {
        return response()->json([
            "status" => 200,
            "error" => null,
            "message" => "Search complete",
            "data" => [
                "book" => $data[0],
                "author" => $data[1],
                "publisher" => $data[2]
            ]
        ], 200);
    }

    function index($keyword)
    {

        $numberOnly = preg_match('/^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$/', $keyword);

        if ($numberOnly) {
            $book = Book::where('isbn', 'like', '%' . $keyword . '%')->get();
            return $this->searchResponse([$book]);
        }
        
        $book = Book::where('title', 'like', '%'.$keyword.'%')->get();
        $author = Author::where('name', 'like', '%'.$keyword.'%')->get();
        $publisher = Publisher::where('name', 'like', '%'.$keyword.'%')->get();

        return $this->searchResponse([$book, $author, $publisher]);
        
    }
}
