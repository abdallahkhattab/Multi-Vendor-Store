<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => [
                'normal' => $this->price,
                'compare' => $this->compare_price
            ],
            'description' => $this->description,
            'image' => $this->image_url,
            'relations' => [
                'category' => $this->category ? [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                ] : null,
                'store' => $this->store ? [
                    'id' => $this->store->id,
                    'name' => $this->store->name,
                ] : null,
            ],
        ];
    }
}
