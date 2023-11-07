<?php

namespace App\Http\Requests;

use App\Services\FormRequestHandleInputs;
use App\Models\languages;
use Illuminate\Foundation\Http\FormRequest;

class sectionsFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $arr = [
            'id'=>'filled',
            'attributes'=>'filled|array',
            'attributes.*'=>'filled|exists:attributes,id',
        ];
        $arr = FormRequestHandleInputs::handle($arr,['name','info']);
        return $arr;

    }


    public function attributes()
    {
        return [
          'ar_name'=>trans('keywords.ar_name'),
          'en_name'=>trans('keywords.en_name'),
          'ar_info'=>trans('keywords.ar_description'),
          'en_info'=>trans('keywords.en_description'),
        ];
    }
}
