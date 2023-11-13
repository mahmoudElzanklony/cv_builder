<?php

namespace App\Http\Resources;

use App\Services\FormRequestHandleInputs;
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
          'name'=>FormRequestHandleInputs::handle_output_column($this->name),
          'type'=>$this->type,
          'visibility'=>$this->visibility,
          'created_at'=>$this->created_at->format('Y h d,h:i A'),

        ];
    }
}
