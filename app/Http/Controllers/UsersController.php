<?php

namespace App\Http\Controllers;

use App\Http\Requests\usersFormRequest;
use App\Http\traits\messages;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
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
}
