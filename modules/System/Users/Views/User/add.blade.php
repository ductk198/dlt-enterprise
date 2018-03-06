<form id="frmAddUser" role="form" action="" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input class="form-control" name="id" type="hidden" value="{{$id}}">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CẬP NHẬT THÔNG TIN NGƯỜI DÙNG</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <span class="radio col-md-4 text-right">Thuộc đơn vị</span>
                            <div class="col-md-8">
                                <input disabled class="form-control input-md" type="text" value="{!! $unitname !!}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <span class="radio col-md-4 text-right">Thuộc phòng ban</span>
                            <div class="col-md-8">
                                <input disabled class="form-control input-md" type="text" value="{!! $departmentname !!}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <span class="radio col-md-4 text-right control-label required">Tên đăng nhập</span>
                            <div class="col-md-8">
                                <input class="form-control input-md" type="text" id="username" name="username" value="{{$data->C_USERNAME}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <span class="radio col-md-4 text-right control-label required">Họ và tên</span>
                            <div class="col-md-8">
                                <input class="form-control input-md" type="text" id="name" name="name" value="{{$data->C_NAME}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <span class="radio col-md-4 text-right {{ $required }}">Mật khẩu</span>
                            <div class="col-md-8">
                                <input class="form-control input-md" type="password" id="password" name="password" value="">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <span class="radio col-md-4 text-right {{ $required }}">Xác nhận MK</span>
                            <div class="col-md-8">
                                <input class="form-control input-md" type="password" id="repassword" name="repassword" value="">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <span class="radio col-md-4 text-right control-label required">Phòng ban</span>
                            <div class="col-md-8">
                                <select id="department" name="department" class="form-control input-sm chzn-select" message="">
                                    @foreach ($departments as $department)
                                    @if($department->PK_UNIT == $parent_department)
                                    <option selected value="{{ $department->PK_UNIT }}">{{ $department->C_NAME }}</option>
                                    @else
                                    <option value="{{ $department->PK_UNIT }}">{{ $department->C_NAME }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <span class="radio col-md-4 text-right control-label required">Chức vụ</span>
                            <div class="col-md-8">
                                <select id="position" name="position" class="form-control input-sm chzn-select" message="">
                                    <option value="">Chọn</option>
                                    @php
                                    function romanic_number($integer, $upcase = true) 
                                    { 
                                    $table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1); 
                                    $return = ''; 
                                    while($integer > 0) 
                                    { 
                                    foreach($table as $rom=>$arb) 
                                    { 
                                    if($integer >= $arb) 
                                    { 
                                    $integer -= $arb; 
                                    $return .= $rom; 
                                    break; 
                                    } 
                                    } 
                                    } 

                                    return $return; 
                                    }
                                    $oldfkpossitiongroup = '';
                                    $outgroup = '';
                                    $i = 1;
                                    foreach ($positions as $position){
                                    if($oldfkpossitiongroup  != $position->FK_POSITION_GROUP){
                                    echo  $outgroup ;
                                    echo  '<optgroup label="'. romanic_number($i).'. '. $position->C_NAME_GROUP.'">';
                                        $oldfkpossitiongroup = $position->FK_POSITION_GROUP;
                                        $outgroup = '</optgroup>';
                                    $i++;
                                    }

                                    if($position->PK_POSITION == $data->FK_POSITION){
                                    echo '<option selected value="'. $position->PK_POSITION .'">'. $position->C_NAME.'</option>';
                                    }else{
                                    echo   '<option value="'.$position->PK_POSITION .'">'. $position->C_NAME. '</option>';
                                    }

                                    }
                                    @endphp
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <span class="radio col-md-4 text-right">Số điện thoại</span>
                            <div class="col-md-8">
                                <input class="form-control input-md" type="text" id="phone_number" name="phone_number" value="{{$data->C_TEL}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <span class="radio col-md-4 text-right">Email</span>
                            <div class="col-md-8">
                                <input class="form-control input-md" type="text" id="email" name="email" value="{{$data->C_EMAIL}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <span class="radio col-md-2 text-right control-label required" style="margin-right:10px;">Vai trò</span>
                            @if($_SESSION["role"] == 'ADMIN_SYSTEM')
                            <div class="col-md-2">
                                <label class="radio"><input name="vaitro" {{ ($data->C_ROLE == 'ADMIN_SYSTEM') ? 'checked' : ''}} type="radio" value="ADMIN_SYSTEM">Quản trị hệ thống</label>
                            </div>
                            @endif
                            <div class="col-md-3">
                                <label class="radio"><input name="vaitro" {{($data->C_ROLE == 'ADMIN_OWNER') ? 'checked' : ''}} type="radio" value="ADMIN_OWNER">Quản trị đơn vị triển khai</label>
                            </div>
                            <div class="col-md-2">
                                <label class="radio"><input name="vaitro" {{($data->C_ROLE == 'USER') ? 'checked' : ''}} type="radio" value="USER">Người dùng</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <span class="radio col-md-4 text-right">Số thứ tự</span>
                            <div class="col-md-4">
                                <input class="form-control input-md" type="text" id="oder" name="oder" value="{{$data->C_ORDER}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <span class="radio col-md-4 text-right">Trạng thái</span>
                            <div class="col-md-8">
                                <label><input type="checkbox" {{$check}} id="status" value="HOAT_DONG" name="status">Hoạt động</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id='btn_update' class="btn btn-primary btn-flat" type="button">{{Lang::get('System::Common.submit')}}</button>
                <button type="input" class="btn btn-default" data-dismiss="modal">{{Lang::get('System::Common.close')}}</button>
            </div>
        </div>
    </div>
</form>
<style>
    .radio-container label.error{
        float: right;
    }
</style>
<script>
    function checkallper(obj, name) {
        if (obj.checked) {
            $('input[name="' + name + '"]').prop('checked', true);
        } else {
            $('input[name="' + name + '"]').prop('checked', false);
        }
    }
</script>