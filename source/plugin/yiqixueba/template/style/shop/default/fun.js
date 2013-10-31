(function ($) {
    $.fn.lazyload = function (options) {
        var settings = {
            threshold: 0,
            failurelimit: 0,
            event: "scroll",
            effect: "show",
            container: window
        };
        if (options) {
            $.extend(settings, options);
        }
        /* Fire one scroll event per scroll. Not one scroll event per image. */
        var elements = this;
        if ("scroll" == settings.event) {
            $(settings.container).bind("scroll", function (event) {
                var counter = 0;
                elements.each(function () {
                    if ($.abovethetop(this, settings) ||
$.leftofbegin(this, settings)) {
                        /* Nothing. */
                    } else if (!$.belowthefold(this, settings) &&
!$.rightoffold(this, settings)) {
                        $(this).trigger("appear");
                    } else {
                        if (counter++ > settings.failurelimit) {
                            return false;
                        }
                    }
                });
                /* Remove image from array so it is not looped next time. */
                var temp = $.grep(elements, function (element) {
                    return !element.loaded;
                });
                elements = $(temp);
            });
        }
        this.each(function () {
            var self = this;
            //我就把这里的一段代码删除了
            /* When appear is triggered load original image. */
            $(self).one("appear", function () {
                if (!this.loaded) {
                    $("<img />")
.bind("load", function () {
    $(self)
.hide()
.attr("src", $(self).attr("original"))
[settings.effect](settings.effectspeed);
    self.loaded = true;
})
.attr("src", $(self).attr("original"));
                };
            });
            /* When wanted event is triggered load original image */
            /* by triggering appear.                              */
            if ("scroll" != settings.event) {
                $(self).bind(settings.event, function (event) {
                    if (!self.loaded) {
                        $(self).trigger("appear");
                    }
                });
            }
        });
        /* Force initial check if images should appear. */
        $(settings.container).trigger(settings.event);
        return this;
    };
    /* Convenience methods in jQuery namespace.           */
    /* Use as  $.belowthefold(element, {threshold : 100, container : window}) */
    $.belowthefold = function (element, settings) {
        if (settings.container === undefined || settings.container === window) {
            var fold = $(window).height() + $(window).scrollTop();
        } else {
            var fold = $(settings.container).offset().top + $(settings.container).height();
        }
        return fold <= $(element).offset().top - settings.threshold;
    };
    $.rightoffold = function (element, settings) {
        if (settings.container === undefined || settings.container === window) {
            var fold = $(window).width() + $(window).scrollLeft();
        } else {
            var fold = $(settings.container).offset().left + $(settings.container).width();
        }
        return fold <= $(element).offset().left - settings.threshold;
    };
    $.abovethetop = function (element, settings) {
        if (settings.container === undefined || settings.container === window) {
            var fold = $(window).scrollTop();
        } else {
            var fold = $(settings.container).offset().top;
        }
        return fold >= $(element).offset().top + settings.threshold + $(element).height();
    };
    $.leftofbegin = function (element, settings) {
        if (settings.container === undefined || settings.container === window) {
            var fold = $(window).scrollLeft();
        } else {
            var fold = $(settings.container).offset().left;
        }
        return fold >= $(element).offset().left + settings.threshold + $(element).width();
    };
    /* Custom selectors for your convenience.   */
    /* Use as $("img:below-the-fold").something() */
    $.extend($.expr[':'], {
        "below-the-fold": "$.belowthefold(a, {threshold : 0, container: window})",
        "above-the-fold": "!$.belowthefold(a, {threshold : 0, container: window})",
        "right-of-fold": "$.rightoffold(a, {threshold : 0, container: window})",
        "left-of-fold": "!$.rightoffold(a, {threshold : 0, container: window})"
    });
})(jQuery);

//lazy load img
$(function(){
    $("img.lazy_img").lazyload({
        effect:"fadeIn"
    });
	
	$(".history_view").hover(function(){
		var t = $(this);
		var view_list = t.find(".view_list");
		t.addClass("view_hover");
		view_list.show();
	},function(){
		var t = $(this);
		var view_list = t.find(".view_list");
		t.removeClass("view_hover");
		view_list.hide();
	});
	//uz
	if($('#uzif').length){
		$('#uzif').html('<iframe src="http://uz.huihunqing.com/" frameborder="0" scrolling="no" style="width:0;height:0;overflow:hidden;"></iframe>');
		}
})

/* backToTop */
$(function(){ 
	var win=$(window);
	var doc=$("body");
	var now_w=doc.width();
	var wrap_s=$(".nomalwidth").css("width");
	var wrap_b=$(".orderwidth").css("width");
	if($(".orderwidth").length>0){
		wrap = parseInt(wrap_b);
	}else{
		wrap = parseInt(wrap_s);
	}
	var L=$(".leftfloat");
	var L_w=L.width();
	var Lmag_w=L_w+8;
	L.css("left",((doc.width()-wrap)/2-Lmag_w)+"px");
	
	var R=$(".rightfloat");
	var R_w=R.width();
	var Rmag_w=R_w+8;
	
	var RF=$(".topic_right");
	var RF_W=RF.width();
	var RFmag_w=RF_W+20;
	
	R.css("right",(((doc.width()-wrap)/2)-Rmag_w)+"px"); 
	RF.css("right",(((doc.width()-wrap)/2)-RFmag_w)+"px");
	$(".adclose").css("right",(((doc.width()-wrap)/2)-70)+"px");
	win.bind("scroll resize",function(){
		var _w=(doc.width()-wrap)/2;		
		if(_w>Rmag_w){
			R.css("right",(_w-Rmag_w)+"px");
			$(".adclose").css("right",(_w-Rmag_w)+"px");
		}else{
			R.css("right","10px");
			$(".adclose").css("right","10px");
		};
		if(_w>Lmag_w){
			L.css("left",(_w-Lmag_w)+"px");
		}else{
			L.css("left","10px");
		};
		if(_w>RFmag_w){
			RF.css("right",(_w-RFmag_w)+"px");
		}else{
			RF.css("right","10px");
		};
		now_w=doc.width();
	});
	
	var $backToTopTxt = "返回顶部", $backToTopEle = $('<a class="backToTop"></a>').appendTo($(".service_content"))
        .text($backToTopTxt).attr("title", $backToTopTxt).click(function() {
            $("html, body").animate({ scrollTop: 0 }, 120);
    }), $backToTopFun = function() {
        var st = $(document).scrollTop(), winh = $(window).height();
		$(".leftfloat").show();
        $(".rightfloat").show();
        //IE6下的定位
        if (!window.XMLHttpRequest) {
            $backToTopEle.css("top", st + winh - 166);
			//$backToTopEle.css("top", document.documentElement.scrollTop+document.documentElement.clientHeight-this.offsetHeight);
        }
    };
    $(window).bind("scroll", $backToTopFun);
    $backToTopFun();
	if($('.goTop').length>0){
		$('.goTop').click(function() {
				$("html, body").animate({ scrollTop: 0 }, 120);
		})
	}
});


// JavaScript Document
$(function(){
	/*leftfloat*/
	$(".leftfloat").hover(function(){
		$(".leftfloat ul li a").each(function(){
			if($(this).attr("select")){
				cname = $(this).attr("select");
				$(this).removeClass(cname);
			}
		})
	},function(){
		$(".leftfloat ul li a").each(function(){
			if($(this).attr("select")){
				cname = $(this).attr("select");
				$(this).addClass(cname);
			}
		})
	});
	
	/*nav select*/
	$(".mycenter").hover(function(){
		$(this).find("dl").show();
	},function(){
		$(this).find("dl").hide();
	});
	
	$(".goodsborder").hover(function(){
		_this=$(this);
		_title=_this.find(".goods_title").find("a");
		_this.addClass("list_hover");
		_title.css("color","#ff4000");
	},function(){
		_this.removeClass("list_hover");
		_title.css("color","#4c4c4c");
	})
	/*sh省份选择*/
	$('input[name="address-list"]').click(function(){
		if($(this).val()==0){
			$('#settings-realname,#s1,#s2,#s3,#settings-address').addClass('validate[required]');
			$('#settings-mobile').addClass('validate[required,custom[mobile]]');
			$('.new_address').show();
		} else {
			$('#settings-realname,#s1,#s2,#s3,#settings-address,#settings-mobile').removeClass('validate[required]');
			$('#settings-mobile').removeClass('validate[required,custom[mobile]]');
			$('.new_address').hide();
		}
	});
	$('#uls1 li').live('click',function(){
		var pid = $(this).attr('sid');
		$.get(
			'/ajax/citys_n.php',
			{pid:pid,action:'province'},
			function(data){
				if(data){
					$('#uls2').html(data).show();
				} else {
					$('#uls2').html('').hide();
				}
				$('#s2').val('');
				$('#s3').val('');
			}
		);
	});
	$('#uls2 li').live('click',function(){
		var pid = $(this).attr('sid');
		var txt = $(this).text();
		$.get(
			'/ajax/citys_n.php',
			{pid:pid,txt:txt,privince:$('#s1').val(),action:'city'},
			function(data){
				if(data){
					$('#uls3').html(data).show();
				} else {
					$('#uls3').html('').hide();
				}
				$('#s3').val('');
			}
		);
	});
	var t_a = []; 
	$(".new_address .firstbox p").live('click',function(){
		var _this=$(this);
		var _input = _this.find("input");
		var _secondbox = _this.parents(".firstbox").find(".secondbox");
		var msg = ''.fstv = '';
		var val = _input.val(); 
		var dt = _secondbox.find('ul').text();
		var _inputid = _input.attr('id');
		if(!dt){
			if(_inputid == 's1'){
				_input.attr('msg','请输入省份名称');
			} else if(_inputid == 's2'){
				if(!$('#s1').val()){
					$('#s1').validationEngine("showPrompt","* 请先输入或选择省份","error");
				} else {
					_input.attr('msg','请输入市区名称');
				}
			} else if(_inputid == 's3'){
				if(!$('#s2').val()){
					$('#s2').validationEngine("showPrompt","* 请先输入或选择市区","error");
				} else {
					_input.attr('msg','请输入区县名称');
				}
			}
			_input.attr('readonly',false).css('cursor','insert').attr('sid','');
		} else {
			if(_inputid == 's1'){
				_input.attr('msg','请选择省份');
			} else if(_inputid == 's2'){
				_input.attr('msg','请选择市区');
			} else if(_inputid == 's3'){
				_input.attr('msg','请选择区县');
			}
			_input.attr('readonly',true).blur().css('cursor','pointer');
		}
		msg = _input.attr('msg');
		if(_secondbox.is(":hidden")){
			if(dt){_secondbox.slideDown('fast'); _this.addClass("selectup");}
			if(!val || !dt){_input.validationEngine("showPrompt","* "+msg,"error");}
		}else{			
			_secondbox.slideUp('fast');
		}
	});
	$(".secondbox ul li").live({mouseenter:function(){
		$(this).css("background","#cccccc");
	},mouseleave:function(){
		$(this).css("background","none");
	}});
	$(".secondbox ul li").live('click',function(){
		$(this).parents(".firstbox").find("input").val($(this).text()).attr('sid',$(this).attr('sid')).validationEngine("hidePrompt");
		$(this).parents(".firstbox").find("p").removeClass("selectup");
		$(".secondbox").slideUp('fast');
	});
	$(".firstbox").each(function(i){
		var _this=$(this);
		_this.hover(function(){	
			clearTimeout(t_a[i]);						
		},function(){
			t_a[i]=setTimeout(function(){
				var _input = _this.find("input");
				var _val = _input.val();
				if(_val){
					_input.validationEngine("hidePrompt");
				}
				_this.find("p").removeClass("selectup");
				_this.find(".secondbox").slideUp('fast');
			},500);							
		})
	});
	
	
	/*首页客服弹窗*/
	$(".boxalert").hover(function(){
		$(".service_box").show();
	},function(){
		$(".service_box").hide();
	})
	
	/*city*/
	$(".citylist ul li").hover(function(){
		var _this = $(this);
		var letter = _this.find(".letter");
		_this.addClass("libg");
		letter.addClass("letter_current");
	},function(){
		var _this = $(this);
		var letter = _this.find(".letter");
		_this.removeClass("libg");
		letter.removeClass("letter_current");
	})
});

/*addfavorite*/
function addfavorite(url,gtitle)
{
	var weburl = 'http://www.pailezu.com';
	var webtitle = '派乐族-品质团购';
	if(url!='') weburl=url;
	if(gtitle!='') webtitle = gtitle;
   try 
   {
      window.external.addFavorite(weburl,webtitle);
   }
   catch(e) {	   
	   try { 
		   window.sidebar.addPanel(title, URL, "");     //firefox 
		} catch(e) { 
		   alert("浏览器不支持，请使用Ctrl+D进行收藏");     //chrome opera safari 
		}
   }
} 

/*JS倒计时*/
function countdown(obj,date){
	var str = arguments[2] ? arguments[2] : "";
	var date_end=new Date(date);
	
	var countdownStart=function(){
		var date_now=new Date();
		var date_diff=date_end.getTime()-date_now.getTime(); 
		var date_time=["day","hour","minute","second"];
		date_time.day=parseInt(date_diff/(1000*60*60*24));
	
		date_time.hour=parseInt(date_diff/(1000*60*60));
		date_time.hour-=date_time.day*24;
	
		date_time.minute=parseInt(date_diff/(1000*60));
		date_time.minute-=date_time.hour*60;
		date_time.minute-=date_time.day*24*60;
	
		date_time.second=parseInt(date_diff/1000);
		date_time.second-=date_time.minute*60;
		date_time.second-=date_time.hour*60*60;
		date_time.second-=date_time.day*24*60*60;
		
		date_time.day=(date_time.day<=0) ? "" : date_time.day+"天";
		date_time.hour=(date_time.day=="" && date_time.hour<=0) ? "" : date_time.hour+"小时";
		date_time.minute=(date_time.hour=="" && date_time.minute<=0) ? "" : date_time.minute+"分";
		date_time.second=(date_time.minute=="" && date_time.second<=0) ? "" : date_time.second+"秒";
		var date_text;
		if(date_time.second==""){
			date_text="已结束";
			clearInterval(countdownGO);
		}else{
			if(str!=''){
				date_text = "<strong>"+date_time.day.replace("天","")+"</strong><strong>"+date_time.hour.replace("小时","")+"</strong><strong>"+date_time.minute.replace("分","")+"</strong><strong>"+date_time.second.replace("秒","")+"</strong>";
			}else{
				date_text="剩余 "+date_time.day+date_time.hour+date_time.minute+date_time.second;
			}
		};
	
		$(obj).html(date_text);
	};

	countdownStart();
	var countdownGO=setInterval(countdownStart,1000)
};

/*修改价格函数*/
function countchange(obj,mod,farefree,credit,ctype){
	var _this = $(obj);
	var input = _this.parent().find("input");
	var count = input.val();	
	var perprice = parseFloat($("#perprice").find("span").html());	
	var total = parseFloat($("#totalsave").val());
	var totalcount = 0;
	var rember = parseInt(input.attr("rember"));	
	/*var reg = /^\d{4}$/;
	if(!reg.test(count)) _this.val(rember);*/
	count = parseInt(count);	
	var needpay;	
	var express = $("#express");
	if(mod==0){
		num = parseInt(count);
		num_a  = parseInt(num+1);
		number = parseInt(num_a-num);
		totalgoods = (parseFloat(total)+parseFloat(perprice)*parseInt(number));
		totalgoods = totalgoods.toFixed(2);
		
	}else if(mod==1){	
		if(count<=0){return false;}	
		num = parseInt(count);
		num_a = parseInt(num-1);
		number = parseInt(num-num_a);
		totalgoods = (parseFloat(total)-parseFloat(perprice)*parseInt(number));
		totalgoods = totalgoods.toFixed(2);
	}else{
		if(rember<count){
			number = parseInt(count-rember);			
			totalgoods = (parseFloat(total)+parseFloat(perprice)*parseInt(number));
			totalgoods = totalgoods.toFixed(2);
		}else{
			number = parseInt(rember-count);
			totalgoods = (parseFloat(total)-parseFloat(perprice)*parseInt(number));
			totalgoods = totalgoods.toFixed(2);
		}
		num_a = count;
	}
	var totalprice = totalgoods;
	input.val(num_a);	
	$(".countdiv input").each(function(){
		totalcount += parseInt($(this).val());
	})
	totalcount = parseInt(totalcount);
	if(farefree>0){
		if(totalcount<farefree){
			totalprice = (parseFloat(totalprice)+parseFloat(8));
			totalprice = totalprice.toFixed(2);
			express.html("&yen;8");
		}else{
			totalprice = (parseFloat(totalprice));
			totalprice = totalprice.toFixed(2);
			express.html("&yen;0");
		}
	}else if(farefree==0){
		totalprice = (parseFloat(totalprice)+parseFloat(8));
		totalprice = totalprice.toFixed(2);
		express.html("&yen;8");
	}else{
		express.html("&yen;0");
	}
    //赠品 
    var need_gift = parseInt($('#need_gift').val());
    var gift_price = parseFloat($('#gift_price').val());
    totalprice = parseFloat(totalprice);
    $('#total_price').val(totalprice);
    if(need_gift > 0 && gift_price > 0){
        totalprice += gift_price;
    }
	
	//满减活动
	if($("#express").length>0){
		var express_fare = parseFloat(($("#express").html()).replace("¥","").replace("&yen;",""));//运费
		var cur_all_price = parseFloat(perprice*totalcount+express_fare);//价格加上运费
	}else{
		var cur_all_price = parseFloat(perprice*totalcount);//价格加上运费
	}
	var cur_price = parseFloat(perprice*totalcount);//纯总价

	var mj_man = parseFloat($("#mj_man").val());
	var mj_jian = parseFloat($("#mj_jian").val());
	if(mj_man>0 && mj_jian>0){
		if(cur_all_price>=mj_man){
			var jian = parseFloat(Math.floor(cur_all_price/mj_man)*mj_jian);
			$("#mj_price_span").html(jian);
			totalprice = parseFloat(cur_all_price-(Math.floor(cur_all_price/mj_man)*mj_jian));
			totalgoods = parseFloat(cur_all_price-(Math.floor(cur_all_price/mj_man)*mj_jian));
		}else{
			$("#mj_price_span").html("0");
			totalprice = parseFloat(cur_all_price+(Math.floor(cur_all_price/mj_man)*mj_jian));
			totalgoods = parseFloat(cur_all_price+(Math.floor(cur_all_price/mj_man)*mj_jian));
		}
	}
	
	$("#quantity").val(totalcount);
	input.attr("rember",num_a);	
	$("#total").html("&yen;"+cur_price);
	$("#totalprice").html("&yen;"+totalprice);
	$("#totalsave").val(totalgoods);
	if(totalcount<=0){
		$("#total").html("&yen;0");
		$("#totalprice").html("&yen;0");
		$("#totalsave").val(0);
	}
	if(ctype){
		needpay = $("#totalsave").val();
		creditrest = parseFloat(needpay)-parseFloat(credit);
		creditrest = creditrest.toFixed(2);
		$("#needpay").html("&yen;"+creditrest);
		select_paytype(credit,totalgoods,total);
	}
}
/*价格改变函数*/
function changeprice(obj){
	var _this = $(obj);
	var lastnum = _this.attr("rember");
	var v = _this.val();
	var juege = false;
	var msg='您最多可以购买9999件';
	var reg = /^\d{0,4}$/;
	if(reg.test(v) && parseInt(v)>0){
		_this.css("color","#000000");
		_this.parent().parent().parent().css("background","#fff9d8");
	}else{
		_this.css("color","#cccccc");
		_this.parent().parent().parent().css("background","none");
	}
	if(/^\d+$/.test(v) == false){
		msg='您只能输入数字';
	}
	if(!reg.test(v)){
		_this.validationEngine("showPrompt",msg,"error");
		setTimeout(function(){
			_this.validationEngine("hidePrompt");
		},2000);
		_this.val(lastnum);
		return false;
	}
}


/*JSON数据函数*/
function JSONtransform(r) {
	var type = r['data']['type'];
	var data = r['data']['data'];
	if (type == 'alert') {
		$.jBox.tip(data,'error');
	} else if (type == 'eval') {
		eval(data);
	} else if (type == 'refresh') {
		window.location.reload();
	} else if (type == 'updater') {
		var id = data['id'];
		var inner = data['html']; 
		jQuery('#' + id).html(inner);
	} else if (type == 'dialog') {
		$.jBox('<div style="padding:16px;">'+data['html']+'</div>', {title: data['title'],width:'auto',buttons:{}});
	}  else if (type == 'mix') {
		for (var x in data) {
			r['data'] = data[x];
			$.JSONtransform(r);
		}
	}
}

/* 修改地址 */
var is_modify_address = false;
function modify_address(id,type){
	$.jBox.tip('努力加载中...','loading');
    $.get(
        '/order/ajax.php?action=modifyaddress&address_id='+id+'&type='+type,
        function(data){
        	$.jBox.closeTip();
            JSONtransform(data);
        },'JSON'
    );
}
/* 删除地址 */
function del_address(id,oid){
    if(confirm('确定要删除该收货地址吗？')){
    	$.jBox.tip('努力加载中...','loading');
        $.get(
            '/order/ajax.php?action=deladdress&address_id='+id+'&id='+oid,
            function(data){
                JSONtransform(data);
                $('#tr-address-list-'+id).remove(); 
                $.jBox.tip('删除地址成功','success');
                $('#tr-address-list-0').addClass('bgcol');
                $('#address-list-0').attr('checked',true);
                $('.new_address').show();
            },'JSON'
        );
    }
}
/*选择支付方式*/
function select_paytype(credit,totle,oldtotle){
	//需在线支付
	if(eval(credit) < eval(totle) && eval(credit) >= eval(oldtotle)){
		var needpay = (totle-credit).toFixed(2);
		$('#online_pay_box').show();
		$('#credit_pay_box').hide();
		$('#pay_tip_box').html('账户余额：<span class="arilfont">&yen;'+credit+'</span>，您的余额不够完成本次付款，还需支付 <span class="arilfont" id="needpay">&yen;'+needpay+'</span>');
		$('#paytype_alipay').attr('checked',true);
		$('.paybox').find('label').removeClass('bankborder');
		$('.zhifubao').addClass('bankborder');
	} else if(eval(credit) >= eval(totle) && eval(credit) < eval(oldtotle)) {
		$('#online_pay_box').hide();
		$('#credit_pay_box').show();
		$('#pay_tip_box').html('账户余额：<span class="arilfont">&yen;'+credit+'</span>，您的余额足够本次购买，请直接确认订单，完成付款。');
		$('#paytype_credit').attr('checked',true);
	}
}