<?php

namespace Modules\System\Position\Requests;

use App\Http\Requests\Request;

class PositionGroup extends Request
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
        //chi validate khi da chot thong tin khach hang
        $return = array();
        $return = [
            'C_NAME'                    => 'required',
            'C_CODE'                    => 'required'
        ];
        return $return;
    }
	
	   /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'C_NAME.required'  => 'Tên chức vụ không được để trống',
            'C_CODE.required'  => 'Mã nhóm chức vụ không được để trống'
        ];
    }
}
