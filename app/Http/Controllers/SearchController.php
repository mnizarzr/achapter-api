<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use App\Resources\BookResource;

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
                "books" => $data[0],
                "authors" => $data[1],
                "publishers" => $data[2]
            ]
        ], 200);
    }

    function index($keyword)
    {

        $numberOnly = preg_match('/^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$/', $keyword);

        if ($numberOnly) {
            $book = Book::where('ISBN', 'like', '%' . $keyword . '%')->get();
            return $this->searchResponse([BookResource::collection($book), [], []]);
        }
        
        $book = Book::where('title', 'like', '%'.$keyword.'%')->get();
        $author = Author::where('name', 'like', '%'.$keyword.'%')->withCount('books')->get();
        $publisher = Publisher::where('name', 'like', '%'.$keyword.'%')->get();

        return $this->searchResponse([BookResource::collection($book), $author, $publisher]);
        
    }
}
