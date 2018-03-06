function fixed_header() {
	$('.panel-header').css('width',$('.content-wrapper').width());
    $('.panel-body').css('padding-top',$('.panel-header').height());
    $( window ).resize(function() {
        $('.panel-header').css('width',$('.content-wrapper').width());
        $('.panel-body').css('padding-top',$('.panel-header').height());
    });
}