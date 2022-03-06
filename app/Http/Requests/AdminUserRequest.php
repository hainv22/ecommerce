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
            'u_email ' => 'required|unique:users,email,' . $this->id,
            'u_name ' => 'required',
            'avatar' => 'image',
            'phone' => 'required|unique:users,phone,' . $this->id,
            'address' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'u_email.required' => 'Email không được phép để trống.',
            'u_email.email' => 'Bạn nhập không phải là email',
            'u_email.unique' => 'Email đã tồn tại trên hệ thống.',
            'u_email.min' => 'Email không được dưới 5 kí tự.',
            'u_name.required' => 'Username không được phép để trống.',
            'phone.required' => 'SĐT không được để trống.',
            'phone.numeric' => 'SĐT phải là chuỗi số',
            'phone.unique' => 'SĐT đã được đăng kí.',
            'address.required' => 'Địa chỉ không được để trống',
        ];
    }
}
