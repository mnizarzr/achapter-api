<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{

    public function __construct()
    {
        $this->middleware('isAdmin', ["only" => ["create", "edit"]]);
    }

    public function index()
    {
        $book = Book::with(['authors:author_id,name', 'publisher:id,name'])->paginate(10);
        return $this->responseSuccess(200, "Fetched all successfully", $book);
    }

    public function create(Request $request)
    {

        $book = new Book();
        $bookDetail = new BookDetail();

        // Book model fields

        $book->ISBN = $request->isbn;
        $book->title = $request->title;
        $book->publishing_date = $request->publishing_date;
        $book->publisher_id = $request->publisher_id;
        $book->created_by = Auth::user()["id"];
        $book->updated_by = Auth::user()["id"];

        // Book detail fields

        $pictureName = "default.png";

        if ($request->hasFile('picture') && $request->file('picture')->isValid()) {
            $pictureName = $this->uploadPicture($request, 'books');
        }

        $bookDetail->price = $request->price;
        $bookDetail->description = $request->description;
        $bookDetail->width = $request->width;
        $bookDetail->height = $request->height;
        $bookDetail->weight = $request->weigth;
        $bookDetail->pages = $request->pages;
        $bookDetail->language = $request->language;
        $bookDetail->discount = $request->discount;
        $bookDetail->stock = $request->stock;
        $bookDetail->pictures = $pictureName;

        $book->save();
        $book->bookDetail()->save($bookDetail);

        if (is_array($request->author_id)) {
            foreach ($request->author_id as $id) {
                $book->authors()->sync($id);
            }
        } else {
            $book->authors()->sync($request->author_id);
        }

        if (is_array($request->genre_id)) {
            foreach ($request->genre_id as $id) {
                $book->genres()->sync($id);
            }
        } else {
            $book->genres()->sync($request->genre_id);
        }

        $book["authors"] = $book->authors()->get();
        $book["genres"] = $book->genres()->get();

        return $this->responseSuccess(200, "Book added", array_merge($book->toArray(), $bookDetail->toArray()));
    }

    public function edit(Request $request, $id)
    {

        $book = Book::find($id);

        if ($book == null) return response()->json([
            "status" => 404,
            "error" => "ERR_NOT_FOUND",
            "message" => "Not found"
        ], 404);

        $bookDetail = BookDetail::find($id);

        // Book model fields

        $book->ISBN = $request->isbn;
        $book->title = $request->title;
        $book->publishing_date = $request->publishing_date;
        $book->publisher_id = $request->publisher_id;
        $book->updated_by = Auth::user()["id"];

        // Book detail fields

        $pictureName = "default.png";

        if ($bookDetail->pictures != "default.png" && !$request->hasFile('picture')) {
            $pictureName = $bookDetail->pictures;
        } else if ($request->hasFile('picture') && $request->file('picture')->isValid()) {
            $pictureName = $this->uploadPicture($request, 'books');
        }

        $bookDetail->price = $request->price;
        $bookDetail->description = $request->description;
        $bookDetail->width = $request->width;
        $bookDetail->height = $request->height;
        $bookDetail->weight = $request->weight;
        $bookDetail->pages = $request->pages;
        $bookDetail->language = $request->language;
        $bookDetail->discount = $request->discount;
        $bookDetail->stock = $request->stock;
        $bookDetail->pictures = $pictureName;

        $book->save();
        $book->bookDetail()->save($bookDetail);

        if (is_array($request->author_id)) {
            foreach ($request->author_id as $id) {
                $book->authors()->sync($id);
            }
        } else {
            $book->authors()->sync($request->author_id);
        }

        if (is_array($request->genre_id)) {
            foreach ($request->genre_id as $id) {
                $book->genres()->sync($id);
            }
        } else {
            $book->genres()->sync($request->genre_id);
        }

        $book["authors"] = $book->authors()->get();
        $book["genres"] = $book->genres()->get();

        $book = Book::select('*')->where(["id" => $id])
            ->with(['authors:author_id,name', 'publisher:id,name', 'genres:name', 'bookDetail'])->get();

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

    public function findByName($name)
    {
        $queryName = str_replace('_', " ", $name);
        $book = Book::where('title', $queryName)->firstOrFail();

        return response($book);
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
