<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "isbn" => $this->ISBN,
            "title" => $this->title,
            "price" => $this->bookDetail->price,
            "picture" => $this->bookDetail->pictures,
            "discount" => $this->bookDetail->discount,
            "bought_count" => $this->bought_count,
            "authors" => AuthorResource::collection($this->authors)
        ];
    }
}
