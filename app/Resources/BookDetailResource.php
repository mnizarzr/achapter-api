<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "isbn" => $this->ISBN,
            "title" => $this->title,
            "genres" => GenreResource::collection($this->genres),
            "publishing_date" => $this->publishing_date,
            "publisher" => $this->publisher->name,
            "dimension" => $this->bookDetail->width . " cm x " . $this->bookDetail->height . " cm",
            "weight" => $this->bookDetail->weight . " kg",
            "language" => $this->bookDetail->language,
            "stock" => $this->bookDetail->stock,
            "pages" => $this->bookDetail->pages,
            "description" => $this->bookDetail->description,
            "price" => $this->bookDetail->price,
            "picture" => $this->bookDetail->pictures,
            "discount" => $this->bookDetail->discount,
            "authors" => $this->authors
        ];
    }
}
