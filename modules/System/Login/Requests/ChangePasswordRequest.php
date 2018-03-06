<?php

namespace Modules\System\Login\Requests;

use App\Http\Requests\Request;
use Lang;

class ChangePasswordRequest extends Request
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
            'old_password' => 'required',
            'new_password' => 'required',
            're_password'  => 'required|same:new_password',
        ];
    }

    public function messages()
    {
        return [
            'old_password.required' => 'Mật khẩu cũ không được để trống',
            'new_password.required' => 'Mật khẩu mới không được để trống',
            're_password.required'  => 'Nhập lại mật khẩu không được để trống',
            're_password.same'      => 'Mật khẩu không khớp',
        ];
    }
}
