<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Author;
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

        $author = Author::all();

        return response()->json(["status" => 200, "data" => $author]);
    }

    public function create(Request $request)
    {

        $pictureName = null;

        if ($request->hasFile('picture') && $request->file('picture')->isValid()) {
            $pictureName = str_replace(" ", "", $request->name) . "_" . Date::now()->toDateString() . "." . $request->file('picture')->getClientOriginalExtension();
            $request->file('picture')->move(base_path('public' . DIRECTORY_SEPARATOR . 'storage'), $pictureName);
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
                "message" => "Author created",
                "data" => $author
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "status" => 409,
                "error" => $e,
                "message" => "Author creation failed"
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        $author = Author::find($id);
        return response()->json($author);
    }

    public function delete(Request $request, $id)
    {
        $author = Author::destroy($id);
        return response()->json($author);
    }

}
