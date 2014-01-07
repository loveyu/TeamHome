// JavaScript Document

/**
 *	中户中心激活验证
 */

$(document).ready(function(){
	//global vars
	var form = $("#user_center_active_form");
	var user_name = $("#user_center_active_username");
	var user_name_info = $("#user_center_active_info");
	
	//On blur
	user_name.blur(validateUserName);

	//On key press
	user_name.keyup(validateUserName);
	
	//On Submitting
	form.submit(function(){
		
		if(validateUserName())
			return true
		else
			return false;
	});
	
	//验证用户名
	function validateUserName(){
		var a = $("#user_center_active_username").val();
		var filter = /^[0-9A-Za-z]+$/;
		if(filter.test(a)){
			$.get(SITE_URL + "tool/check_info.php?type=username&value="+a,function(result){
				if(result=='true'){
					user_name.addClass("error");
					user_name_info.addClass("error");
					user_name_info.text("该用户名已经被使用");
					return false;
				}else if(result=='error'){
					user_name.addClass("error");
					user_name_info.addClass("error");
					user_name_info.text("该名称不符合规范");
					return false;
				}	
			});
			user_name.removeClass("error");
			user_name_info.text("可以使用该用户名");
			user_name_info.removeClass("error");
			return true;
		}else{
			user_name.addClass("error");
			user_name_info.addClass("error");
			user_name_info.text("该名称不符合规范");
			return false;
		}
	}
});
