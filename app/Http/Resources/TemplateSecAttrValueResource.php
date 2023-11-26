<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TemplateSecAttrValueResource extends JsonResource
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
          'template_section_id'=>$this->template_section_id,
          'template_section'=>TemplateSectionResource::make($this->whenLoaded('template_section')),
          'attribute'=>AttributeResource::make($this->whenLoaded('attribute')),
          'answer'=>$this->answer,
          'answer_type'=>$this->answer_type,
          'content_width'=>$this->content_width,
          'created_at'=>$this->created_at,
        ];
    }
}
