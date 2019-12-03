<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class AuthorController extends Controller {


    public function __construct(){
        $this->middleware('isadmin');
    }

    public function showAll()
    {

    }

}

?>