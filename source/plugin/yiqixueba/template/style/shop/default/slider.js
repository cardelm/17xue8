// JavaScript Document
$(function(){
	var index = 0;
	var adtimer;
	var _wrap = $("#slide_container ul"); //
	var len = $("#slide_container ul li").length;
	//len=1;
	if (len > 1) {
		$("#slide_container").hover(function() {
			clearInterval(adtimer);
		},
		function() {
			adtimer = setInterval(function() {

				var _field = _wrap.find('li:first'); //此变量不可放置于函数起始处,li:first取值是变化的
				var _h = _field.height(); //取得每次滚动高度(多行滚动情况下,此变量不可置于开始处,否则会有间隔时长延时)
				_field.animate({
					marginTop: -_h + 'px'
				},
				500,
				function() { //通过取负margin值,隐藏第一行
					_field.css('marginTop', 0).appendTo(_wrap); //隐藏后,将该行的margin值置零,并插入到最后,实现无缝滚动
				})

			},
			5000);
		}).trigger("mouseleave");
		function showImg(index) {
			var Height = $("#slide_container").height();
			$("#slide_container ol").stop().animate({
				marginTop: -_h + 'px'
			},
			1000);
		}

		$("#slide_container").mouseover(function() {
			$("#slide_container .mouse_direction").css("display", "block");
		});
		$("#slide_container").mouseout(function() {
			$("#slide_container .mouse_direction").css("display", "none");
		});
	}

	$("#slide_container").find(".mouse_top").click(function() {
		var _field = _wrap.find('li:first'); //此变量不可放置于函数起始处,li:first取值是变化的
		var last = _wrap.find('li:last'); //此变量不可放置于函数起始处,li:last取值是变化的
		//last.prependTo(_wrap);
		var _h = last.height();
		$("#slide_container ul").css('marginTop', -_h + "px");
		last.prependTo(_wrap);
		$("#slide_container ul").animate({
			marginTop: 0
		},
		500,
		function() { //通过取负margin值,隐藏第一行
			//$("#slide_container ol").css('marginTop',0).prependTo(_wrap);//隐藏后,将该行的margin值置零,并插入到最后,实现无缝滚动
		})
	});
	$("#slide_container").find(".mouse_bottom").click(function() {
		var _field = _wrap.find('li:first'); //此变量不可放置于函数起始处,li:first取值是变化的
		var _h = _field.height();
		_field.animate({
			marginTop: -_h + 'px'
		},
		500,
		function() { //通过取负margin值,隐藏第一行
			_field.css('marginTop', 0).appendTo(_wrap); //隐藏后,将该行的margin值置零,并插入到最后,实现无缝滚动
		})
	});
})