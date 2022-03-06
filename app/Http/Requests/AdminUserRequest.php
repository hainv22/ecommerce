<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserRequest extends FormRequest
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
            'email' => 'required|unique:users,email,' . $this->id,
            'name' => 'required',
            'phone' => 'required|unique:users,phone,' . $this->id,
            'address' => 'required',
        ];
    }
    public function messages()
    {
        return[
            'email.required' => 'Email không được phép để trống.',
            'email.unique' => 'Email đã tồn tại trên hệ thống.',
            'name.required' => 'Username không được phép để trống.',
            'phone.required' => 'SĐT không được để trống.',
            'phone.unique' => 'SĐT đã được đăng kí.',
            'address.required' => 'Địa chỉ không được để trống',
        ];
    }
}
