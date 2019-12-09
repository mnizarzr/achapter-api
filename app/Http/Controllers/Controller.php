<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    protected function responseSuccess($status, $message, $data = null)
    {
        return response()->json([
            "status" => $status,
            "error" => null,
            "message" => $message,
            "data" => $data
        ], $status);
    }

    protected function responseError($status, $error, $message)
    {
        return response()->json([
            "status" => $status,
            "error" => $error,
            "message" => $message
        ], $status);
    }

    protected function generatePictureName($name, $ext)
    {      
        return str_replace(" ", "", $name) . "_" . Date::now()->toDateString() . "." . $ext;
    }

    protected function uploadPicture(Request $request, $path)
    {
        $name = $request->title ?: $request->name;
        $pictureName = str_replace(" ", "", $name) . "_" . Date::now()->toDateString() . "." . $request->file('picture')->getClientOriginalExtension();
        $request->file('picture')->move(base_path('public' . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $path), $pictureName);
        return $pictureName;
    }

}
