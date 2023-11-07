<?php


namespace App\Http\traits;


use App\Services\FormRequestHandleInputs;
use App\Http\Actions\ImageModalSave;
use App\Http\Controllers\Filters\NameFilter;
use App\Http\Requests\sectionsFormRequest;
use App\Http\Requests\templatesFormRequest;
use App\Http\Resources\SectionResource;
use App\Http\Resources\TemplateResource;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

trait SectionsHelper
{
    use upload_image;
    public static function all_sections(){
        $sections = \App\Models\sections::query()->with('attributes')->orderBy('id','DESC');
        $output = app(Pipeline::class)
            ->send($sections)
            ->through([
                NameFilter::class
            ])
            ->thenReturn()
            ->paginate(10);
        return SectionResource::collection($output);
    }

    public function save_section(sectionsFormRequest $request){
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data =  FormRequestHandleInputs::handle_inputs_langs($data,['name','info']);

        DB::beginTransaction();
        $section = \App\Models\sections::query()->updateOrCreate([
            'id'=>$data['id'] ?? null
        ],$data);

        if(request()->hasFile('image')){
            $image = $this->upload(request('image'),'sections');
            ImageModalSave::make($section->id,'sections','sections/'.$image);
        }

        if(request()->has('attributes')){
            $section->attributes()->sync(request('attributes'));
        }
        DB::commit();
        return messages::success_output(trans('messages.saved_successfully'),SectionResource::make($section));
    }
}
