<?php


namespace App\Http\traits;


use App\Http\Controllers\Filters\LabelFilter;
use App\Http\Requests\AttributeFormRequest;
use App\Http\Resources\AttributeResource;
use App\Models\attributes;
use App\Services\FormRequestHandleInputs;
use App\Http\Actions\ImageModalSave;
use App\Http\Controllers\Filters\NameFilter;
use App\Http\Requests\sectionsFormRequest;

use App\Http\Resources\SectionResource;

use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

trait AttributesHelper
{
    use upload_image;
    public static function all_attributes(){
        $sections = attributes::query()->orderBy('id','DESC');
        $output = app(Pipeline::class)
            ->send($sections)
            ->through([
                LabelFilter::class
            ])
            ->thenReturn()
            ->paginate(10);
        return AttributeResource::collection($output);
    }

    public function save_attribute(AttributeFormRequest $request){
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data =  FormRequestHandleInputs::handle_inputs_langs($data,['label','placeholder']);

        DB::beginTransaction();
        $output = \App\Models\attributes::query()->updateOrCreate([
            'id'=>$data['id'] ?? null
        ],$data);


        DB::commit();
        return messages::success_output(trans('messages.saved_successfully'),AttributeResource::make($output));
    }
}
