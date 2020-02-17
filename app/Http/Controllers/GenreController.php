<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Http\Request;
use App\Resources\BookResource;

class GenreController extends Controller
{

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->middleware('isAdmin', ["except" => ["index", "getAllBooks"]]);
    }

    public function index()
    {
        $genres = Genre::all();
        $genres->makeHidden(['created_at', 'updated_at'])->toBase();
        return $this->responseSuccess(200, "Fetched all successfully", $genres);
    }

    public function create()
    {
        $genre = Genre::create($this->request->toArray());
        return $this->responseSuccess(200, "Genre added", $genre);
    }

    public function edit($id)
    {
        Genre::where(['id' => $id])->update(["name" => $this->request->name]);
        $genre = genre::find($id);
        return $this->responseSuccess(200, "Genre updated", $genre);
    }

    public function delete($id)
    {
        Genre::destroy($id);
        return $this->responseSuccess(200, "Genre deleted");
    }

    public function getAllBooks($id)
    {
        $genre = Genre::where(["id" => $id])->get();
        $books = Book::with(['bookDetail', 'authors'])->whereHas('genres', function ($query) use ($id) {
            $query->whereGenreId($id);
        })->get();

        $genre->makeHidden(['created_at', 'updated_at'])->toBase();
        
        $genre[0]['books'] = BookResource::collection($books);

        return $this->responseSuccess(200, "Fetched all successfully", $genre[0]);
    }
}
