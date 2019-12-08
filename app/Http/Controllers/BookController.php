<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Symfony\Component\Console\Helper\Helper;

class BookController extends Controller
{

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->middleware('isAdmin', ["only" => ["create", "edit"]]);
    }

    public function index()
    {
        $book = Book::with(['authors:author_id,name', 'publisher:id,name'])->get();
        return response()->json([
            "status" => 200,
            "error" => null,
            "message" => "Fetched all successfully",
            "data" => $book
        ]);
    }

    public function create()
    {

        $book = new Book();
        $bookDetail = new BookDetail();

        $book->ISBN = $this->request->isbn;
        $book->title = $this->request->title;
        $book->created_by = Auth::user()["id"];
        $book->updated_by = Auth::user()["id"];

        $bookDetail->price = $this->request->price;
        $bookDetail->stock = $this->request->stock;

        $pictureName = null;

        if ($this->request->hasFile('picture') && $this->request->file('picture')->isValid()) {
            $pictureName = str_replace(" ", "", $this->request->title) . "_" . Date::now()->toDateString() . "." . $this->request->file('picture')->getClientOriginalExtension();
            $this->request->file('picture')->move(base_path('public' . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'books'), $pictureName);
        }

        $bookDetail->pictures = $pictureName;

        $book->save();
        $book->bookDetail()->save($bookDetail);

        if (is_array($this->request->author_id)) {
            foreach ($this->request->author_id as $id) {
                $book->authors()->attach($id);
            }
        } else {
            $book->authors()->attach($this->request->author_id);
        }

        $book["authors"] = $book->authors()->get();

        return $this->responseSuccess(200, "Book added", array_merge($book->toArray(), $bookDetail->toArray()));
    }

    public function edit($id)
    {

        $book = Book::find($id);

        if ($book == null) return response()->json([
            "status" => 404,
            "error" => "ERR_NOT_FOUND",
            "message" => "Not found"
        ], 404);

        if (is_array($this->request->genre_id)) {
            foreach ($this->request->genre_id as $id) {
                $book->genres()->attach($id);
            }
        } else {
            $book->genres()->sync($this->request->genre_id);
        }
        // $book->publishing_date = $this->request->publishing_date;
        // $book->publisher_id = $this->request->publisher_id;
        $book->updated_by = Auth::user()["id"];

        $book->save();

        $book = Book::select('*')->where(["id" => $id])
            ->with(['authors:author_id,name', 'publisher:id,name', 'genres:name'])->get();

        return $this->responseSuccess(200, "Book updated", $book[0]);
    }

    public function find($id)
    {

        $book = Book::find($id);

        if ($book == null) return response()->json([
            "status" => 404,
            "error" => "ERR_NOT_FOUND",
            "message" => "Not found"
        ], 404);

        $book = Book::select('*')->where(["id" => $id])
            ->with(['authors:author_id,name', 'publisher:id,name', 'genres:name'])->get();

        return $this->responseSuccess(200, "Book found", $book[0]);
    }

    public function delete($id)
    {

        $book = Book::find($id);

        $book->authors()->detach();
        $book->bookDetail()->delete();
        $book->delete();

        return $this->responseSuccess(200, "Deleted successfully");
    }
}
