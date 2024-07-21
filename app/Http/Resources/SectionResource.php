<?php

namespace App\Http\Resources;

use App\Services\FormRequestHandleInputs;
use Illuminate\Http\Resources\Json\JsonResource;
use TheSeer\Tokenizer\Exception;

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
        $arr =  [
          'id'=>$this->id,
          'name'=>FormRequestHandleInputs::handle_output_column($this->name),
          'en_name'=>json_decode($this->name, true)['en'],
          'info'=>FormRequestHandleInputs::handle_output_column($this->info),
          'image'=>ImageResource::make($this->whenLoaded('image')) ,
          'attributes'=>AttributeResource::collection($this->whenLoaded('attributes')),
          'created_at'=>$this->created_at->format('Y h d,h:i A') ?? null,
        ];
        try{
            $arr['profile_name']=json_decode($this->name, true)[app()->getLocale().'_profile'] ?? '';
        }catch (Exception $e){}
        return $arr;
    }
}
