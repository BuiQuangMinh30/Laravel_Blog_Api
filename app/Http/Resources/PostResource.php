<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'image'=>asset('images/'.$this->image),
            'title'=>$this->title,
            'body'=>$this->body,
            'category_title'=>$this->category->title,
            'category'=>$this->category_id,
            'author'=>$this->author->name
        ];
    }
}
