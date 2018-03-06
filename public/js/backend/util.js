// Load Modul tu sidebar
function LoadModul(url,Module,SubModul = ''){
  showloaddingloadlist();
	data = '';
    $.ajax({
      url: url,
      type: "Get",
      //cache: true,
      data:data,
      success: function(arrResult){
      	$("#main-content").html(arrResult);
        $("li").removeClass('active');
        $("#"+Module).addClass("active");
      }  
  }); 
}
// Load file Js va Css

// Hien thi thong bao
function AlertMessage(type,label,message,s = 2000){
    var vclass = 'alert';
    lclass = 'fa';
    if(type=='success'){
      vclass += ' alert-success';
      lclass += ' fa-check';
    }else if(type=='info'){
      vclass += ' alert-info';
      lclass += ' fa-info';
    }else if(type=='warning'){
      vclass += ' alert-warning';
      lclass += ' fa-warning';
    }else if(type=='danger'){
      vclass += ' alert-danger';
      lclass += ' fa-warning';
    }
    $("#message-alert").alert();
    $("#message-alert").removeClass();
    $("#message-alert").addClass(vclass);
    $("#message-icon").removeClass();
    $("#message-icon").addClass(lclass);
    $("#message-label").html(label);
    $("#message-infor").html(message);
    $("#message-alert").fadeTo(s, 500).slideUp(500, function(){
      $("#message-alert").slideUp(500);
    })
}
function showloaddingloadlist(){
  $("#loadding").show();
}
function showloaddingsearch(){
  $('.search').button('loading');
}
function showmainloadding(){
  $(".main_loadding").show();
}
function successLoad(){
  $("#loadding").hide();
   $(".main_loadding").hide();
  $(".search").button('reset');
}

function cb(start, end) {
        $('input[name="daterange"]').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }
function daterangepicker(obj){
    var currentTime = new Date();
    var lastyear = currentTime.getFullYear() - 1;
    var start = moment().startOf('month');
    var end = moment().endOf('month');
    obj.daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Hôm nay': [moment(), moment()],
           'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Tháng này': [moment().startOf('month'), moment().endOf('month')],
           'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
           'Năm này': [moment().startOf('year'), moment().endOf('year')],
           'Năm trước': [moment([lastyear, 00, 01]), moment([lastyear, 11, 31])]
        },locale: {
            format: 'DD/MM/YYYY',
            customRangeLabel: "Tùy chọn",
            "daysOfWeek": [
                "CN",
                "T2",
                "T3",
                "T4",
                "T5",
                "T6",
                "T7"
            ],
            "monthNames": [
            "Tháng một",
            "Tháng hai",
            "Tháng ba",
            "Tháng bốn",
            "Tháng năm",
            "Tháng sáu",
            "Tháng bẩy",
            "Tháng tám",
            "Tháng chín",
            "Tháng mười",
            "Tháng mười một",
            "Tháng mười hai"
            ],
            "firstDay": 1,
            "applyLabel": "Lưu lại",
            "cancelLabel": "Hủy",
        },"alwaysShowCalendars": true
    }, cb);

    cb(start, end);
}
// Load file Js va Css
function loadfileJsCss(arrUrl){
    var count = arrUrl.length;
    for (var i = 0; i < count; i++){
        if(arrUrl[i]['type'] ==='js'){
            $('head').append('<script src="' + arrUrl[i]['src'] + '" type="text/javascript" charset="utf-8"></script>');
        }else{
            $('head').append('<link rel="stylesheet" type="text/css" href="' + arrUrl[i]['src'] + '">');
        }
    };
}