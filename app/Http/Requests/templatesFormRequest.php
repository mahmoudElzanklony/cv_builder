<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class templatesFormRequest extends FormRequest
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
        return [
            'id'=>'filled',
            'category_id'=>'required|exists:categories,id',
            'ar_name'=>'required',
            'en_name'=>'required',
            'image'=>'filled|image|mimes:jpg,png,jpeg,gif',
        ];
    }

    public function attributes()
    {
        return [
          'category_id'=>trans('keywords.category'),
          'ar_name'=>trans('keywords.ar_name'),
          'en_name'=>trans('keywords.en_name'),
        ];
    }
}
