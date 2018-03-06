<?php

namespace Modules\System\Enterprise\Requests;

use App\Http\Requests\Request;

class Enterprise extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        //chi validate khi da chot thong tin khach hang
        $return = array();
        $return = [
            'MASOTHUE' => 'required',
            'TENCONGTY' => 'required'
        ];
        return $return;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function messages() {
        return [
            'MASOTHUE.required' => 'Mã số thuế không được để trống',
            'TENCONGTY.required' => 'Tên công ty không được để trống'
        ];
    }

}
