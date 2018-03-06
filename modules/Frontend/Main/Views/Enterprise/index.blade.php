@extends('Frontend.layouts.index')
@section('content')
<style>
    p{
        font-size: 15px;
    }
    span>a{
        font-size: 15px;
    }
</style>
<div class="main-header">
    <div class="container">
        <div style="font-weight: bold" id="title-huongdan">
            <span><a class="breadcrumb1" role="button"> <h2>Thông tin {{$arrDataSingle->TENCONGTY}}</h2></a></span>
        </div>
        <form class="form-inline" id="frmviewrecord_index">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="blog-item-inner">
                <div class="article-content">
                    <p>{{$arrDataSingle->TENCONGTY}}</p>

                    <p>Tên giao dịch: {{$arrDataSingle->TENGIAODICH}}</p>

                    <p>Mã số thuế: {{$arrDataSingle->MASOTHUE}}</p>

                    <p>Ngày cấp: {{$arrDataSingle->NGAYCAP}}</p>

                    <p>Địa chỉ trụ sở chính: {{$arrDataSingle->DIACHITRUSOCHINH}}</p>

                    <p>Người đại diện pháp luật: {{$arrDataSingle->NGUOIDAIDIEN}}</p>

                    <p>Địa chỉ người đại diện: {{$arrDataSingle->DIACHINGUOIDAIDIEN}}</p>

                    <p>Số điện thoại: {{$arrDataSingle->SODIENTHOAI}}</p>

                    <p>Email:{{$arrDataSingle->EMAIL}}</p>

                    <!--<p>Website: http://www.samsung.com.vn</p>-->

                    <p>Ngành nghề chính: {{$arrDataSingle->NGANHNGHECHINH}}</p>

                    <p>Lĩnh vực kinh tế: {{$arrDataSingle->LINHVUCKINHTE}}</p>

                    <p>Giới thiệu:</p>

                    <!--<p>Công Ty TNHH Samsung Electronics Việt Nam (SEV) chính thức đi vào hoạt động vào tháng 4 năm 2009. SEV là nhà máy sản xuất điện thoại quy mô hoàn chỉnh tại Việt Nam với tổng vốn đầu tư 670 triệu USD – sau nâng lên 1,5 tỷ USD vào năm 2012. Năm 2016, Công ty ghi nhận doanh thu đạt 19,426 tỷ won, tương đương 408,147 tỷ đồng. Samsung hiện nay đầu tư 6 nhà máy tại Việt Nam. Tổng vốn đầu tư của các dự án là 15 tỷ USD và đã giải ngân được 10 tỷ USD. Tính đến năm 2017, các nhà máy tại Việt Nam đã xuất khẩu sản phẩm tới 52 quốc gia và vùng lãnh thổ.</p>-->
                </div>

            </div>
        </form>
    </div>
</div>
@endsection