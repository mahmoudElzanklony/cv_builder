<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TemplateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
          'id'=>$this->id,
          'category'=>CategoryResource::make($this->whenLoaded('category')),
          'name'=>$this->{app()->getLocale().'_name'},
          'created_at'=>$this->created_at->format('Y h d,h:i A'),

        ];
    }
}
