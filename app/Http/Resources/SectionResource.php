<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
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
          'name'=>$this->{app()->getLocale().'_name'},
          'info'=>$this->{app()->getLocale().'_info'},
          'created_at'=>$this->created_at->format('Y h d,h:i A'),
        ];
    }
}
