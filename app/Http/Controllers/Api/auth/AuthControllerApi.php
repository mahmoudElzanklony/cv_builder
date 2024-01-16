<?php

namespace App\Http\Controllers\Api\auth;

use App\Http\Controllers\classes\auth\AuthServicesClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\usersFormRequest;
use App\Http\traits\messages;
use App\Models\roles;
use App\Models\User;
use App\Services\auth\register_service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthControllerApi extends AuthServicesClass
{
    use messages;
    public function login_api(){

        $data = Validator::make(request()->all(),[
            'email'=>'required',
            'password'=>'required',
        ]);
        if(sizeof($data->errors()) == 0) {

            $credential = request()->only(['email', 'password']);
            $token = auth('api')->attempt($credential);
            if(!$token){
               // return response()->json('error in email or password',401);
                return response()->json(['errors'=>trans('errors.unauthenticated')],401);
            }else {
                $user = auth('api')->user();
                $user['token'] =  $token;
                $user['role'] = roles::query()->find($user->role_id);
                return $user;
                return [
                    'user'=>$user,
                    'token'=>$token
                ];
            }
        }else{
            return response()->json($data->errors(),401);
           // return messages::error_output($data->errors());
        }
    }

    public function validate_user(){
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        return response()->json($user);
    }


    public function logout_api(){
        session()->forget('type');
        auth()->logout();
        return messages::success_output('logout successfully');

    }

    public function user(){
        //$user = User::query()->find()
        //$user['role'] = roles::query()->find($user->role_id);
        return JWTAuth::parseToken()->authenticate();
    }
}
