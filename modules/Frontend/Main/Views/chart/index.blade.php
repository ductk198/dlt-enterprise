@extends('Backend.layouts.index')
@section('content')

<script type="text/javascript">
    $('li').removeClass();
    $('li#slbd').addClass('nav-active');
    var arrJsCss = $.parseJSON('<?php echo $strJsCss; ?>');
    EfyLib.loadFileJsCss(arrJsCss);
</script>
<div class="main-header">
    <div style="font-weight: bold" id="title-huongdan">
        <span><a class="breadcrumb1" role="button"><i id="get-data" class="fa fa-home" aria-hidden="true"></i> BIỂU ĐỒ THỐNG KÊ TÌNH HÌNH GIẢI QUYẾT HỒ SƠ CÁC ĐƠN VỊ</a></span>
    </div>
    <form class="form-inline" id="frmviewchart_index">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</div>
<div id="main-content" class="panel panel-default">     
    <!-- Content Wrapper. Contains page content -->
    <div class="col-md-6 chart-pie-dh">
        <!-- Main content -->
        <section class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" id="frmpie_index">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <select class="form-control" name="year" id="year">
                            {!! $htmYear !!}
                        </select>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="UnitLevel" id="UnitLevel">
                            <option value="">Chọn cấp đơn vị</option>
                            {!! $htmUnitLevel !!}
                        </select>
                    </div>
                    <div class="form-group" style="width: 40%;">
                        <select style="width: 100%" class="form-control ownerunit" name="ownerunit" id="ownerunit">
                            <option value="">Chọn đơn vị</option>
                            {!! $htmlUnit !!}
                        </select>
                    </div>
                </form>
            </div>
            <div class="panel-body">
                <div class="row">
                    <canvas id="pieChart_index" width="350" height="187"></canvas>
                </div>
                <div class="row">
                    <span class="note-chart" id="total-record"></span>
                    <br>
                    <span class="note-chart" id="total-daxl"></span>
                    <br>
                    <span class="note-chart" id="note-dh"></span>
                    <br>
                    <span class="note-chart" id="note-qh"></span>
                </div>
            </div>
        </section>
    </div>
    <!--  <div class="col-md-8" style="padding-left:0px !important">
        <section class="panel panel-default pieChart">
          <div class="panel-heading">
            <form class="form-inline" id="frmbarchar_index">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                  <select class="form-control" name="year" id="year">
                    {!! $htmYear !!}
                  </select>
                </div>
                <div class="form-group">
                  <select class="form-control" name="UnitLevel" id="UnitLevel">
                    <option value="">-- Chọn cấp đơn vị --</option>
                    {!! $htmUnitLevel !!}
                  </select>
                </div>
                <div class="form-group">
                  <select class="form-control ownerunit" name="ownerunit" id="ownerunit">
                    <option value="">-- Chọn đơn vị --</option>
                    {!! $htmlUnit !!}
                  </select>
                </div>
            </form>
          </div>
          <div class="panel-body">
              <div class="row">
                  <canvas id="barChart_month" width="740" height="270"></canvas>
              </div>
          </div>
        </section>
      </div>-->
    <div class="col-md-12">
        <section class="panel panel-default barChart_unit">
            <div class="panel-heading">
                <form class="form-inline" id="frmbarcharunit_index">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <select class="form-control" name="year" id="year">
                            {!! $htmYear !!}
                        </select>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="UnitLevel" id="UnitLevel">
                            <option value="">-- Chọn cấp đơn vị --</option>
                            {!! $htmUnitLevel !!}
                        </select>
                    </div>
                    <div class="form-group">
                        <select class="form-control ownerunit" name="ownerunit" id="ownerunit">
                            <option value="">-- Chọn đơn vị --</option>
                            {!! $htmlUnit !!}
                        </select>
                    </div>
                    <div class="form-group" style="display: none">
                        <select class="form-control">
                            <option>-- Chọn lĩnh vực --</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="panel-body">
                <div class="row">
                    <canvas id="barChart_unit" width="740" height="220"></canvas>
                </div>
            </div>
        </section>
    </div>
</div>
<!-- Hien thi modal -->
<div class="modal fade" id="modal_inforRecord" role="dialog"></div>
<!-- Hien thi modal -->
<div class="modal fade" id="addChart" role="dialog">
</div>
<script type="text/javascript">
    var baseUrl = '{{ url("") }}';
    var Js_Viewchart = new Js_Viewchart(baseUrl, 'backend', 'chart');
    jQuery(document).ready(function ($) {
        Js_Viewchart.loadIndex();
    })
</script>
@endsection