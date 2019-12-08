<?php

namespace App\Http\Controllers;

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
}
