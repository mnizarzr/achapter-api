<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    private $request;

    function __construct(Request $request)
    {
        $this->request = $request;
        $this->middleware('hasJWT');
    }

    public function findByUser($id){

    }

    public function create($id){
        if(Auth::user()["id"] == $id){
            
            $books = $this->request->books;
            $arrBooks = [];
            foreach($books as $book){
                $json = json_decode($book, true);
                array_push($arrBooks, array("book_id" => $json['book_id'], "book_price" => $json['book_price'], "quantity" => $json['quantity']));
            }

            return response($arrBooks);

        }
        return $this->responseError(401, "ERR_UNAUTHORIZED", "Unauthorized request");
    }

}