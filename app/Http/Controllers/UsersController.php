<?php

namespace App\Http\Controllers;

use App\Http\Requests\usersFormRequest;
use App\Http\traits\messages;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\traits\upload_image;
class UsersController extends Controller
{
    use upload_image;
    //
    public function save(usersFormRequest $formRequest){
        $data = $formRequest->validated();
        if(request()->filled('password')){
            $data['password'] = bcrypt(request('password'));
        }
        $output = User::query()->updateOrCreate([
            'id'=>request()->has('id') ? request('id'):null
        ],$data);
        return messages::success_output(trans('messages.saved_successfully'),$output);
    }

    public function update_personal_info(usersFormRequest $usersFormRequest){
        $data = $usersFormRequest->validated();
        if(request()->filled('password')) {
            $data['password'] = bcrypt(request('password'));
        }
        if(request()->hasFile('image')){
            $image = $this->upload(request()->file('image'),'users');
            $data['image'] = $image;
        }
        if(auth()->check()) {
            User::query()->where('id', auth()->id())->update($data);
            $output = User::query()->find(auth()->id());
        }else{
            $output = User::query()->where('activation_code','=', request('activation_code'))->update($data);
        }
        return messages::success_output(trans('messages.updated_successfully'),$output);
    }
}
