<?php


namespace App\Http\traits;


use App\Http\Actions\ImageModalSave;
use App\Http\Controllers\Filters\NameFilter;
use App\Http\Requests\templatesFormRequest;
use App\Http\Resources\TemplateResource;
use App\Services\FormRequestHandleInputs;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
trait TemplatesHelper
{
    use upload_image;
    public function all_templates(){
        $templates = \App\Models\templates::query()->with('category')->orderBy('id','DESC');
        $output = app(Pipeline::class)
                 ->send($templates)
                  ->through([
                    NameFilter::class
                 ])
                ->thenReturn()
                ->paginate(10);
        return TemplateResource::collection($output);
    }

    public function save_template(templatesFormRequest $request){
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data =  FormRequestHandleInputs::handle_inputs_langs($data,['name']);
        DB::beginTransaction();
        $template = \App\Models\templates::query()->updateOrCreate([
            'id'=>$data['id'] ?? null
        ],$data);

        if(request()->hasFile('image')){
            $image = $this->upload(request('image'),'templates');
            ImageModalSave::make($template->id,'templates','templates/'.$image);
        }
        DB::commit();
        return messages::success_output(trans('messages.saved_successfully'),TemplateResource::make($template));
    }
}
