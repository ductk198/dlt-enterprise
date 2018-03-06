<?php $__env->startSection('content'); ?>
<!-- /.content  --> 
<style>
    .header{
        font-weight: bold!important;
    }
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
        padding: 2px;
    }
</style>
<script type="text/javascript">
    $('li').removeClass();
    $('li#thxld').addClass('nav-active');
    var arrJsCss = $.parseJSON('<?php echo $strJsCss; ?>');
    DLTLib.loadFileJsCss(arrJsCss);
</script>
<div class="container">
    <div class="col-xs-12 col-sm-9">
        <h1 style="margin-top: 0">Tìm kiếm thông tin doanh nghiệp</h1>
        <div>
            <form class="t-form form_action" action="" method="">
                <div class="col-sm-12 input-group">
                    <input type="text" class="form-control input-lg" placeholder="Điền tên công ty hoặc mã số thuế" name="search" value="<?php echo e($search); ?>">
                    <div class="input-group-btn">
                        <button class="btn btn-default btn-lg" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                    </div>
                </div>
            </form>
        </div>
        <h2>Các công ty mới được cập nhật</h2>    
        <div>
            <?php 
            foreach($arrData as $key =>$value){
                echo ' <div class="search-results">';
                echo '     <a href="/company/CONG-TY-TNHH-DAU-TU-VA-THUONG-MAI-TAM-DUC-PHAT-03971.html">'.$value->TENCONGTY.'</a> <p>';
                echo '     <strong>Địa chỉ</strong>: <span>'.$value->DIACHITRUSOCHINH.'</span><br>';
                echo '     Mã số thuế: <a href="/company/CONG-TY-TNHH-DAU-TU-VA-THUONG-MAI-TAM-DUC-PHAT-03971.html">';
                echo $value->MASOTHUE;
                echo ' </div>';
            }
             ?>
        </div>    
    </div>
    <div class="col-xs-12 col-sm-3" id="sidebar" role="navigation">
        <div class="list-group">
            <?php 
            foreach($arrTinh as $key =>$value){
                $select ='';
                if($value->C_CODE == $tinhSelected)
                    $select = 'active';
                echo '<a href="/'.$value->C_SHORTCUT.'" class="list-group-item '.$select.'" value="'.$value->C_CODE.'">'.$value->C_NAME.'</a>';
            }
             ?>

            <a href="/" class="list-group-item">TP Hà Nội</a>
        </div>
    </div>

</div>
<div id="duclt">
</div>
<div class="modal fade" id="modal_inforRecord" role="dialog"></div>
<div class="modal fade" id="search_infor_record" role="dialog"></div>
<script type="text/javascript">
    var baseUrl = '<?php echo e(url("")); ?>';
    var Js_Viewrecord = new Js_Viewrecord(baseUrl, 'backend', 'main');
    jQuery(document).ready(function ($) {
//        Js_Viewrecord.loadIndex();
    })
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Frontend.layouts.index', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>