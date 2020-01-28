<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Resources\BookResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class AuthorController extends Controller
{

    public function __construct()
    {
        $this->middleware('isAdmin');
    }

    public function index()
    {

        $authors = Author::withCount('books')->get();

        return $this->responseSuccess(200, "Fetched all successfully", $authors);
    }

    public function create(Request $request)
    {

        $pictureName = "default.png";

        if ($request->hasFile('picture') && $request->file('picture')->isValid()) {

            $ext = $request->file('picture')->getClientOriginalExtension() != 'tmp' ? $request->file('picture')->getClientOriginalExtension() : 'jpg';

            $pictureName = str_replace(" ", "", $request->name) . "_" . Date::now()->toDateString() . "." . $ext;
            $request->file('picture')->move(base_path('public' . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'authors'), $pictureName);
        }

        try {
            $author = new Author;
            $author->name = $request->name;
            $author->picture = $pictureName;
            $author->biography = $request->biography;

            $author->save();

            return response()->json([
                "status" => 201,
                "error" => null,
                "message" => "Author added",
                "data" => $author
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "status" => 409,
                "error" => $e,
                "message" => "Add author failed"
            ], 409);
        }
    }

    public function edit(Request $request, $id)
    {
        $author = Author::find($id);

        if ($author == null) return $this->responseError(404, "ERR_NOT_FOUND", "Author not found");

        $pictureName = "default.png";

        if ($request->hasFile('picture') && $request->file('picture')->isValid()) {

            $ext = $request->file('picture')->getClientOriginalExtension() != 'tmp' ? $request->file('picture')->getClientOriginalExtension() : 'jpg';

            $pictureName = str_replace(" ", "", $request->name) . "_" . Date::now()->toDateString() . "." . $request->file('picture')->getClientOriginalExtension();
            $request->file('picture')->move(base_path('public' . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'authors'), $pictureName);
        }

        $author->name = $request->name ?: $author->name;
        $author->picture = $request->hasFile('picture') ? $pictureName : $author->picture;
        $author->biography = $request->biography ?: $author->biography;
        $author->save();

        return $this->responseSuccess(200, "Author updated", $author);
    }

    public function delete($id)
    {
        Author::destroy($id);
        return $this->responseSuccess(200, "Author deleted successfully");
    }

    public function getAllBooks($id)
    {
        $author = Author::where(["id" => $id])->withCount('books')->get();
        $books = Book::with('bookDetail')->whereHas('authors', function ($query) use ($id) {
            $query->where('author_id', $id);
        })->get();

        $author->makeHidden(['created_at', 'updated_at']);
        $books->makeHidden(['created_at', 'updated_at', 'created_by', 'updated_by']);

        $author[0]['books'] = BookResource::collection($books);

        return $this->responseSuccess(200, "Fetched all successfully", $author[0]);
    }
}
