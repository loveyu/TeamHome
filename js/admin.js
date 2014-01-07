// JavaScript Document

$(document).ready(function(){
	//修改菜单状态
	$(".menu").click(function(){
		if($(this).find(".sub-menu").is(":hidden")){
			$(".sub-menu").hide();
			$(this).find(".sub-menu").show(300);			
		}else{
			$(this).find(".sub-menu").hide(300);
		}
	});
	
	//修改宽度及高度
	fixedWidthHeight();
	$(window).resize(fixedWidthHeight);
	setTimeout(TimeOutFixHeight,5000);
});
function TimeOutFixHeight(){
	fixedWidthHeight();
	setTimeout(TimeOutFixHeight,5000);
}
function fixedWidthHeight(){
	if($("#left_nv1").height() < $(window).height()){
		$("#left_nv1").height($(window).height());
	}
	if($("#left_nv1").height()>$(window).height()){
		if($("#left_nv2").height()>$(window).height()){
			$("#left_nv1").height($("#left_nv2").height());
		}else{
			$("#left_nv1").height($(window).height());
		}
	}
	if($("#zxcontent").height()>$("#left_nv1").height()){
		$("#left_nv1").height($("#zxcontent").height());
	}
	
	if($("#zxcontent").width()<($(window).width()-$("#left_nv1").width()-10)){
		$("#zxcontent").width($(window).width()-$("#left_nv1").width()-10);
	}
	if(($(window).width()-$("#left_nv1").width()-10)<700){
		$("#zxcontent").width(700);
	}
	if($("#zxcontent").width()>700 && ($("#zxcontent").width()+$("#left_nv1").width()+10)>$(window).width()){
		$("#zxcontent").width($(window).width()-$("#left_nv1").width()-10);
	}
}