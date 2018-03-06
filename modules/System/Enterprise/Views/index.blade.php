@extends('Backend.layouts.index')
@section('content')

<!-- /.content  --> 
<script type="text/javascript">
    var arrJsCss = $.parseJSON('<?php echo $strJsCss; ?>');
    loadfileJsCss(arrJsCss);
</script>
<form action="index" method="POST" id="frmSystem_index">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<section class="main-footer">
	<ol class="breadcrumb">
		<li><a href="{{Request::fullUrl()}}"><i class="fa fa-home fa-3"></i></a></li>
		<li class="active">System</li>
	</ol>
	<div class="panel panel-default">
		<div class="panel-body">
			<!-- Màn hình danh sách -->
            <div class="row" id="table-container"></div>
            <!-- Phân trang dữ liệu -->
            <div class="row" id="pagination"></div>
		</div>
    </div>
</section>
</form>
<!-- Hien thi modal -->
<div class="modal fade" id="addSystem" role="dialog">
</div>
<script type="text/javascript">
    var baseUrl = '{{ url("") }}';
    var Js_System = new Js_System(baseUrl,'system');
    jQuery(document).ready(function($) {
        Js_System.loadIndex();
})
</script>
@endsection