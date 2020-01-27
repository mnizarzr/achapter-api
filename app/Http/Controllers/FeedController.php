<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class FeedController extends Controller
{

    private function feedResponse($data)
    {

        return response()->json([
            "status" => 200,
            "error" => null,
            "message" => "Feed fetched succesfully",
            "data" => [
                [
                    "feed_name" => "new_release",
                    "title" => "New Release",
                    "data" => $data[0]
                ],
                [
                    "feed_name" => "promo",
                    "title" => "Promo",
                    "data" => $data[1]
                ]
            ]
        ], 200);
    }

    public function index()
    {

        $newRelease = Book::with('bookDetail')->whereMonth('publishing_date', Date::now()->month)->get();
        $promo = Book::with('bookDetail')->whereHas('bookDetail', function (Builder $query) {
            $query->where('discount', '>', 0);
        })->get();

        return $this->feedResponse([$newRelease, $promo]);
    }

    public function getFeed($feedName)
    {

        $response = null;

        switch ($feedName) {
            case "new_release":
                $response = Book::whereMonth('publishing_date', Date::now()->month)->get();
                break;
            case "best_seller":
                $response = Book::with('bookDetail')->orderBy('bought_count', 'desc')->get();
                break;
        }

        return $this->responseSuccess(200, "Success", $response);
    }
}
