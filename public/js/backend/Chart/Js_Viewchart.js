/*
 * Creater: Duclt
 * Date:01/12/2016#
 * Idea: Lop xu ly lien quan den loai danh muc#
 */
function Js_Viewchart(baseUrl, module, controller) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    this.urlPath = baseUrl + '/' + module + '/' + controller;//Biên public lưu tên module
}

// Load su kien tren man hinh index
Js_Viewchart.prototype.loadIndex = function () {
    var myClass = this;
    $("#footer").show();
    EfyLib.daterangepicker($('input[name="daterange"]'));
    var frmpie_index = 'form#frmpie_index';
    myClass.loadlist(frmpie_index, 0);
    // update data
    $("#get-data").click(function () {
        myClass.updatedata(frmpie_index);
    });
    // Form ty le dang xu ly
    $(frmpie_index).find('#year').change(function () {
        myClass.loadlist(frmpie_index, 1);
    });
    $(frmpie_index).find('#UnitLevel').change(function () {
        var unitlever = $(this).val();
        myClass.loadlistype(1, frmpie_index, unitlever);
    });
    $(frmpie_index).find('#ownerunit').change(function () {
        myClass.loadlist(frmpie_index, 1);
    });
    // Form ty le dung han qua han
    var frmpiechart_index = 'form#frmpiechart_index';
    $(frmpiechart_index).find('#year').change(function () {
        myClass.loadlist(frmpiechart_index, 2);
    });
    $(frmpiechart_index).find('#UnitLevel').change(function () {
        var unitlever = $(this).val();
        myClass.loadlistype(2, frmpiechart_index, unitlever);
    });
    $(frmpiechart_index).find('#ownerunit').change(function () {
        myClass.loadlist(frmpiechart_index, 2);
    });
    // Form ty le dung han, qua han
    var frmbarcharunit_index = 'form#frmbarcharunit_index';
    $(frmbarcharunit_index).find('#year').change(function () {
        myClass.loadlist(frmbarcharunit_index, 3);
    });
    $(frmbarcharunit_index).find('#UnitLevel').change(function () {
        var unitlever = $(this).val();
        myClass.loadlistype(3, frmbarcharunit_index, unitlever);
    });
    $(frmbarcharunit_index).find('#ownerunit').change(function () {
        myClass.loadlist(frmbarcharunit_index, 3);
    });
    // Form ty le dung han, qua han
    var frmChart_index = 'form#frmbarchar_index';
    $(frmChart_index).find('#year').change(function () {
        myClass.barchart_month(frmChart_index);
    });
    $(frmChart_index).find('#UnitLevel').change(function () {
        var unitlever = $(this).val();
        myClass.loadlistype(4, frmChart_index, unitlever);
    });
    $(frmChart_index).find('#ownerunit').change(function () {
        myClass.barchart_month(frmChart_index);
    });
    // Form tinh hinh xu ly ho so trong nam
    $('#top').click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 300);
        return false;
    });
    // load them neu scroll xuong cuoi
    /**var win = $(window);
     win.scroll(function() {
     if ($(document).height() - win.height() == win.scrollTop()) {
     if ($('.barChart_month').css('display') == 'none') {
     myClass.barchart_month(frmChart_index);
     return false;
     }
     }
     });**/
}

// Load su kien tren cac minh khac
Js_Viewchart.prototype.loadlist = function (oForm, type) {
//    return false;
    var myClass = this;
    var stringunit = '';
    var checksearch = false;
    // lay don vi tim kiem $(oForm_barchar).find('#search')
    $(oForm).find(".ownerunit > option").each(function () {
        if (this.value !== '') {
            checksearch = true;
            if (stringunit !== '') {
                stringunit = stringunit + ',' + this.value;
            } else {
                stringunit = this.value;
            }
        }
    })
    if (!checksearch) {
        EfyLib.alertMessage('danger', "Cảnh báo", "Không tìm thấy đơn vị để tìm kiếm!");
        return false;
    }
    if ($(oForm).find(".ownerunit").val() !== '') {
        stringunit = $(oForm).find(".ownerunit").val();
    }
    var myChart;
    var url = myClass.urlPath + '/loadlist';
    var data = $(oForm).serialize();
    data += '&stringunit=' + stringunit;
    data += '&type=' + type;
    data += '&UnitLevel=' + $(oForm).find("#UnitLevel option:selected").val();
    data += '&currentUnit=' + $('#ownerunit').val();
//  EfyLib.showmainloadding();
    $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        //cache: true,
        data: data,
        success: function (arrResult) {
            EfyLib.successLoadImage();
            // myClass.barchart($("#barChart"),arrResult['bar']['label'],arrResult['bar']['datadunghan'],arrResult['bar']['dataquahan'],arrResult['bar']['status']);
            // doughnut chart
            if (type == 0 || type == 1) {
//                if (arrResult['Pie_doughnut']) {
                myClass.view_pie_doughnut($("#pieChart_index"), arrResult['Pie_doughnut']);
//                }
            }
            if (type == 0 || type == 2) {
                if (arrResult['Barchart_month']) {
                    myClass.view_barchart_month($("#barChart_month"), arrResult['Barchart_month']);
                }
            }
            if (type == 0 || type == 3) {
//                if (arrResult['Barchart_unit']) {
                myClass.view_barchart($("#barChart_unit"), arrResult['Barchart_unit']);
//                }
            }
            // default pie chart
        }
    });
}

// update data moi
Js_Viewchart.prototype.updatedata = function (oForm) {
    var myClass = this;
    var checksearch = false;
    var stringunit = '';
    var myChartpie;

    // lay don vi tim kiem $(oForm_barchar).find('#search')
    $(oForm).find(".ownerunit > option").each(function () {
        if (this.value !== '') {
            checksearch = true;
            if (stringunit !== '') {
                stringunit = stringunit + ',' + this.value;
            } else {
                stringunit = this.value;
            }
        }
    })
    if (!checksearch) {
        EfyLib.alertMessage('danger', "Cảnh báo", "Không tìm thấy đơn vị để tìm kiếm!");
        return false;
    }
    EfyLib.showmainloadding();
    if ($(oForm).find(".ownerunit").val() !== '') {
        stringunit = $(oForm).find(".ownerunit").val();
    }
    EfyLib.showmainloadding();
    var url = myClass.urlPath + '/UpdateData';
    var data = $(oForm).serialize();
    data += '&stringunit=' + stringunit;
    $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        //cache: true,
        data: data,
        success: function (arrResult) {
            EfyLib.successLoadImage();
        }
    });
}

// Load su kien tren cac minh khac
Js_Viewchart.prototype.Pie_default = function (oForm) {
    var myClass = this;
    var checksearch = false;
    var stringunit = '';
    var myChartpie;
    // lay don vi tim kiem $(oForm_barchar).find('#search')
    $(oForm).find(".ownerunit > option").each(function () {
        if (this.value !== '') {
            checksearch = true;
            if (stringunit !== '') {
                stringunit = stringunit + ',' + this.value;
            } else {
                stringunit = this.value;
            }
        }
    })
    if (!checksearch) {
        EfyLib.alertMessage('danger', "Cảnh báo", "Không tìm thấy đơn vị để tìm kiếm!");
        return false;
    }
    EfyLib.showmainloadding();
    if ($(oForm).find(".ownerunit").val() !== '') {
        stringunit = $(oForm).find(".ownerunit").val();
    }
    var url = myClass.urlPath + '/piechart';
    var data = $(oForm).serialize();
    data += '&stringunit=' + stringunit;
    $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        //cache: true,
        data: data,
        success: function (arrResult) {
            myClass.piechart_unit(arrResult);
            $(window).scrollTop($(document).height() - 300);
        }
    });
}

// Load su kien tren cac minh khac
Js_Viewchart.prototype.barchart_month = function (oForm) {
    var myClass = this;
    var stringunit = '';
    var checksearch = false;
    // lay don vi tim kiem $(oForm_barchar).find('#search')
    $(oForm).find(".ownerunit > option").each(function () {
        if (this.value !== '') {
            checksearch = true;
            if (stringunit !== '') {
                stringunit = stringunit + ',' + this.value;
            } else {
                stringunit = this.value;
            }
        }
    })
    if (!checksearch) {
        EfyLib.alertMessage('danger', "Cảnh báo", "Không tìm thấy đơn vị để tìm kiếm!");
        return false;
    }
    if ($(oForm).find(".ownerunit").val() !== '') {
        stringunit = $(oForm).find(".ownerunit").val();
    }
    var myChart;
    var url = myClass.urlPath + '/barchart_month';
    var data = $(oForm).serialize();
    data += '&stringunit=' + stringunit;
    EfyLib.showmainloadding();
    $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        //cache: true,
        data: data,
        success: function (arrResult) {
            myClass.view_barchart_month($("#barChart_month"), arrResult['Barchart_month']);
            $('.barChart_month').show();
            EfyLib.successLoadImage();
        }
    });
}

// Load su kien tren cac minh khac
Js_Viewchart.prototype.showbarchart_unit = function (oForm) {
    var myClass = this;
    var stringunit = '';
    var checksearch = false;
    // lay don vi tim kiem $(oForm_barchar).find('#search')
    $(oForm).find(".ownerunit > option").each(function () {
        if (this.value !== '') {
            checksearch = true;
            if (stringunit !== '') {
                stringunit = stringunit + ',' + this.value;
            } else {
                stringunit = this.value;
            }
        }
    })
    if (!checksearch) {
        EfyLib.alertMessage('danger', "Cảnh báo", "Không tìm thấy đơn vị để tìm kiếm!");
        return false;
    }
    EfyLib.showmainloadding();
    if ($(oForm).find(".ownerunit").val() !== '') {
        stringunit = $(oForm).find(".ownerunit").val();
    }
    var myChartunit;
    var url = myClass.urlPath + '/barchart';
    var data = $(oForm).serialize();
    data += '&type=' + 2;
    data += '&stringunit=' + stringunit;
    $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        //cache: true,
        data: data,
        success: function (arrResult) {
            myClass.barchart_unit($("#barChart_unit"), arrResult['label'], arrResult['datadunghan'], arrResult['dataquahan'], arrResult['status']);
            EfyLib.successLoadImage();
            $(".barChart_unit").show();
        }
    });
}

// hien thi man hinh barchar
Js_Viewchart.prototype.barchart_unit = function (obj, label, datadunghan, dataquahan, statusview) {
    var data = {
        labels: label,
        "datasets": [
            {
                "label": "Đúng hạn",
                "backgroundColor": "#0470B4",
                "borderWidth": 1,
                "data": datadunghan,
            },
            {
                "label": "Quá hạn",
                "backgroundColor": "#F56954",
                "borderWidth": 1,
                "data": dataquahan,
            }
        ]
    };
    if (typeof myChartunit !== "undefined") {
        myChartunit.destroy();
    }
    myChartunit = new Chart(obj, {
        type: 'bar',
        data: data,
        options: {
            scales: {
                xAxes: [{
                        stacked: statusview,
                        barPercentage: 0.5
                    }],
                yAxes: [{
                        stacked: statusview
                    }]
            },
            barStrokeWidth: true,
        },

    });

}

Js_Viewchart.prototype.loadlistype = function (type, oform, unitlever) {
    console.log(oform);
//    var myClass = this;
    var url = 'dropdownunit';
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: {
            unitlever: unitlever,
            _token: $("input[name=_token]").val()
        },
        success: function (string) {
            $(oform + ' select#ownerunit option').not(':first').remove();
            $(oform + ' select#ownerunit').html(string);

        }
    });
}

Js_Viewchart.prototype.view_pie_doughnut = function (obj, data) {
//    console.log(data);
    var totaldxl = data['danggiaiquyet'] + data['dagiaiquyet'];
//    $("#total-record").html("Đã tiếp nhận: <span class='infor-record-bl'>" + data[2] + "</span> hồ sơ.");
    $("#total-daxl").html("Tổng hồ sơ tiếp nhận: <span class='infor-record-bl'>" + totaldxl + "</span> hồ sơ.");
    $("#note-dh").html("- Đã giải quyết: <span class='infor-record-bl'>" + data['dagiaiquyet'] + "</span> hồ sơ.");
    $("#note-qh").html("- Đang giải quyết: <span class='infor-record-rd'>" + data['danggiaiquyet'] + "</span> hồ sơ.");

    var datashow = [data['dagiaiquyet'], data['danggiaiquyet']];
    var pt = 0;
    if (totaldxl > 0) {
        pt = Math.round((data['dagiaiquyet'] * 100) / totaldxl);
    }
    if (typeof myChartpie !== "undefined") {
        myChartpie.destroy();
    }
    Chart.pluginService.register({
        beforeDraw: function (chart) {
            if (chart.config.options.elements.center) {
                //Get ctx from string
                var ctx = chart.chart.ctx;

                //Get options from the center object in options
                var centerConfig = chart.config.options.elements.center;
                var fontStyle = centerConfig.fontStyle || 'Arial';
                var txt = centerConfig.text;
                var color = centerConfig.color || '#000';
                var sidePadding = centerConfig.sidePadding || 20;
                var sidePaddingCalculated = (sidePadding / 100) * (chart.innerRadius * 2)
                //Start with a base font of 30px
                ctx.font = "30px " + fontStyle;

                //Get the width of the string and also the width of the element minus 10 to give it 5px side padding
                var stringWidth = ctx.measureText(txt).width;
                var elementWidth = (chart.innerRadius * 2) - sidePaddingCalculated;

                // Find out how much the font can grow in width.
                var widthRatio = elementWidth / stringWidth;
                var newFontSize = Math.floor(30 * widthRatio);
                var elementHeight = (chart.innerRadius * 2);

                // Pick a new font size so it will not be larger than the height of label.
                var fontSizeToUse = Math.min(newFontSize, elementHeight);

                //Set font settings to draw it correctly.
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                var centerX = ((chart.chartArea.left + chart.chartArea.right) / 2);
                var centerY = ((chart.chartArea.top + chart.chartArea.bottom) / 2);
                ctx.font = fontSizeToUse + "px " + fontStyle;
                ctx.fillStyle = color;

                //Draw text in center
                ctx.fillText(txt, centerX, centerY);
            }
        }
    });

    var config = {
        type: 'doughnut',
        data: {
            labels: [
                "Đã giải quyết",
                "Đang giải quyết"
            ],
            datasets: [{
                    data: datashow,
                    backgroundColor: [
                        "#0470B4",
                        "#F56954"
                    ]
                }]
        },
        options: {
            elements: {
                center: {
                    text: pt + '%',
                    color: '#025484', // Default is #000000
                    fontStyle: 'Arial', // Default is Arial
                    sidePadding: 30 // Defualt is 20 (as a percentage)
                },
                percentageInnerCutout: 80,
            },
        }
    };
    myChartpie = new Chart(obj, config);
}

Js_Viewchart.prototype.view_pie_default = function (obj, arrResult) {
    var data = {
        labels: arrResult['label'],
        datasets: [
            {
                data: arrResult['data'],
                backgroundColor: [
                    "#0470B4",
                    "#F56954"
                ]
            }]
    };

    myChartpie = new Chart(obj, {
        type: 'pie',
        data: data
    });
}

// hien thi man hinh barchar
Js_Viewchart.prototype.view_barchart = function (obj, arrResult) {
    var label = arrResult['label'];
    var datadunghan = arrResult['dagiaiquyet'];
    var dataquahan = arrResult['dangiaiquyet'];
    var statusview = arrResult['status'];
    var statusview = false;
    var data = {
        labels: label,
        "datasets": [
            {
                "label": "Đã giải quyết",
                "backgroundColor": "#0470B4",
                "borderWidth": 1,
                "data": datadunghan,
            },
            {
                "label": "Đang giải quyết",
                "backgroundColor": "#F56954",
                "borderWidth": 1,
                "data": dataquahan,
            }
        ]
    };
    if (typeof myChart !== "undefined") {
        myChart.destroy();
    }
    myChart = new Chart(obj, {
        type: 'bar',
        data: data,
        options: {
            scales: {
                xAxes: [{
                        stacked: statusview
                    }],
                yAxes: [{
                        stacked: statusview
                    }]
            },
            animation: {
                duration: 1,
                onComplete: function () {
                    var chartInstance = this.chart,
                            ctx = chartInstance.ctx;
                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';

                    this.data.datasets.forEach(function (dataset, i) {
                        var meta = chartInstance.controller.getDatasetMeta(i);
                        meta.data.forEach(function (bar, index) {
                            var data = dataset.data[index];
                            ctx.fillText(data, bar._model.x, bar._model.y - 5);
                        });
                    });
                }
            }
        },

    });

}

// hien thi man hinh barchar
Js_Viewchart.prototype.view_barchart_month = function (obj, arrResult) {
    var label = arrResult['label'];
    var datadunghan = arrResult['datadunghan'];
    var dataquahan = arrResult['dataquahan'];
    var statusview = arrResult['status'];
    var data = {
        labels: label,
        "datasets": [
            {
                "label": "Đã giải quyết",
                "backgroundColor": "#0470B4",
                "borderWidth": 1,
                "data": datadunghan,
            },
            {
                "label": "Đang giải quyết",
                "backgroundColor": "#F56954",
                "borderWidth": 1,
                "data": dataquahan,
            }
        ]
    };
    if (typeof myChart_month !== "undefined") {
        myChart_month.destroy();
    }
    myChart_month = new Chart(obj, {
        type: 'bar',
        data: data,
        options: {
            scales: {
                xAxes: [{
                        stacked: statusview
                    }],
                yAxes: [{
                        stacked: statusview
                    }]
            },
            animation: {
                duration: 1,
                onComplete: function () {
                    var chartInstance = this.chart,
                            ctx = chartInstance.ctx;
                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';

                    this.data.datasets.forEach(function (dataset, i) {
                        var meta = chartInstance.controller.getDatasetMeta(i);
                        meta.data.forEach(function (bar, index) {
                            var data = dataset.data[index];
                            ctx.fillText(data, bar._model.x, bar._model.y - 5);
                        });
                    });
                }
            }
        },

    });

}