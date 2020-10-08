<?php

namespace App\Http\Resources;

use App\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public $resource = Product::class;

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'sku' => $this->sku,
            //'stock_quantity' => $this->stock_quantity,
            'category_id' => $this->category_id,
            //'variation_id' => $this->variation_id,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),

            'image' => asset('images/multiple-images/' . ($this->product_images()->first()->path ?? '')),
            'brochure' => asset('images/multiple-images/' . $this->brochure),
            'analysis' => asset('images/multiple-images/' . $this->analysis),
            'images' => $this->product_images->map(function ($image) {
                return [
                    'path' => asset('images/multiple-images/' . $image->path),
                ];
            }),
            'variations'=> $this->variation,
            'variationValues'=>$this->variatonValues,
            'variation_stock'=>$this->variation()->sum('stock_quantity'),
            'category' => new CategoryResource($this->whenLoaded('category')),
           // 'variation' => new VariationResource($this->whenLoaded('variation')),
           // 'attributes' => AttributeResource::collection($this->whenLoaded('attributes')),
           // 'attributeValues' => AttributeValueResource::collection($this->whenLoaded('attributeValues')),
            //'variations' => VariationResource::collection($this->whenLoaded('variation')),
            //'variationValues' => VariationValueResource::collection($this->whenLoaded('variatonValues')),
        ];
    }
}
