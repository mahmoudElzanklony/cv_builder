<?php


namespace App\Services;


use App\Models\languages;
use Illuminate\Support\Str;

class FormRequestHandleInputs
{
    public static function handle($all , $inputs){
        $langs = languages::query()->select('prefix')->get();
        foreach($langs as $lang){
            foreach($inputs as $input){
                $all[$lang->prefix.'_'.$input] = (app()->getLocale() == $lang->prefix ? 'required':'filled' ) ;
                $all[$lang->prefix.'_'.$input] = (app()->getLocale() == $lang->prefix ? 'required':'filled' ) ;
            }
        }
        return $all;
    }

    public static function handle_inputs_langs($all,$decoded){
        $langs = languages::query()->select('prefix')->get()->map(function ($e){
            return $e->prefix;
        });
        $output = [];
        foreach($all as $name => $value){
            $exist_inner_arr = 0;
            foreach ($langs as $lang) {
                if (Str::contains($name, $lang)) {
                    $input_name = Str::replace($lang,'',$name);
                    $input_name = Str::replace('_','',$input_name);
                    $output[$input_name][$lang] = $value;
                    $exist_inner_arr = 1;
                }
            }
            if($exist_inner_arr == 0){
                $output[$name] = $value;
            }
        }
        foreach ($decoded as $value){
            $output[$value] = json_encode($output[$value],JSON_UNESCAPED_UNICODE);
        }
        return $output;
    }

    public static function handle_output_column($value){
        if($value != '') {
            $value = json_decode($value, true);
            return $value[app()->getLocale()];
        }
        return '';
    }
}
