<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->middleware('isAdmin', ["except" => ["index"]]);
    }

    public function index()
    {
        $genres = Genre::all();
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
        $books = Genre::select('*')->where(["id" => $id])->with('books')->get();
        return $this->responseSuccess(200, "Fetched all successfully", $books[0]);
    }
    
}
