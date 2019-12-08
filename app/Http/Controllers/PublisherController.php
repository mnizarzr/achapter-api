<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->middleware('isAdmin', ["except" => ["index", "getAllBooks"]]);
    }

    public function index()
    {
        $publishers = Publisher::all();
        return $this->responseSuccess(200, "Fetched all successfully", $publishers);
    }

    public function create()
    {
        $publisher = Publisher::create($this->request->toArray());
        return $this->responseSuccess(200, "Publisher added", $publisher);
    }

    public function edit($id)
    {
        Publisher::where(['id' => $id])->update(["name" => $this->request->name]);
        $publisher = Publisher::find($id);
        return $this->responseSuccess(200, "Publisher updated", $publisher);
    }

    public function delete($id)
    {
        Publisher::destroy($id);
        return $this->responseSuccess(200, "Publisher deleted");
    }

    public function getAllBooks($id)
    {
        $books = Publisher::select('*')->where(["id" => $id])->with('books')->get();
        return $this->responseSuccess(200, "Fetched all successfully", $books[0]);
    }
    
}
