<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Filters\AttributeId;
use App\Http\Controllers\Filters\TemplateId;
use App\Http\Controllers\Filters\TemplateSectionId;
use App\Http\Controllers\Filters\UserId;
use App\Http\Resources\UserCvResource;
use App\Models\users_cvs;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class UsersCvsController extends Controller
{
    //
    public function index(){
        $data = users_cvs::query()->with(['user','template'])->orderBy('id','DESC');
        $output = app(Pipeline::class)
            ->send($data)
            ->through([
                TemplateId::class,
                UserId::class,
            ])
            ->thenReturn()
            ->paginate(15);
        return UserCvResource::collection($output);
    }

    public function save(){
        $output = users_cvs::query()->updateOrCreate([
            'id'=>request('id') ?? null
        ],[
            'user_id'=>auth()->id(),
            'template_id'=>request('template_id')
        ]);
        $output = users_cvs::query()->orderBy('id','DESC')->with(['user','template'])->find($output->id);
        return UserCvResource::make($output);
    }
}
