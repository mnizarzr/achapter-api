<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Resources\BookResource;
use Illuminate\Database\Eloquent\Builder;
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
                ],
                [
                    "feed_name" => "best_seller",
                    "title" => "Best Seller",
                    "data" => $data[2]
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
        $bestSeller = Book::orderBy('bought_count', 'desc')->get();

        return $this->feedResponse([BookResource::collection($newRelease), BookResource::collection($promo), BookResource::collection($bestSeller)]);
    }

    public function getFeed($feedName)
    {

        $response = null;

        switch ($feedName) {
            case "new_release":
                $book = Book::whereMonth('publishing_date', Date::now()->month)->get();
                $response = BookResource::collection($book);
                break;
            case "best_seller":
                $book = Book::orderBy('bought_count', 'desc')->get();
                $response = BookResource::collection($book);
                break;
        }

        return $this->responseSuccess(200, "Success", $response);
    }
}
