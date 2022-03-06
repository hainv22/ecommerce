<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminTransportRequest extends FormRequest
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
            'tp_name' => 'required',
            'tp_fee' => 'required',
            'tp_description' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'tp_name.required' => 'Dữ Liệu không để trống',
            'tp_fee' => 'Dữ Liệu không để trống',
            'tp_description' => 'Dữ Liệu không để trống',
        ];
    }
}
