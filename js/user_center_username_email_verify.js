// JavaScript Document

/**
 *	中户中心用户名和邮箱修改
 */

$(document).ready(function(){

	var is_edit_username = $("#edit_user_name_check_box");
	var is_edit_email = $("#edit_user_email_check_box");
	var get_old_email_vcode = $("#get_user_email_code");
	var get_new_email_code = $("#get_new_email_code");
	var vemail = $("#vemail");
	var new_username = $("#new_username");
	var username_info = $("#username_info");
	var new_email = $("#email");
	var new_email_info = $("#email_info");
	var vnemail = $("#vnemail");
	var vnemail_info = $("#vnemail_info");
	var edit_user_form = $("#edit_user_form");
	
	edit_user_form.submit(function(){
		if(!change_username() && !change_email()){
			alert("必须选择一个操作");
			return false;
		}
		if(change_username() && !v_new_username()){
			alert("请检查新的用户名");
			return false;
		}
		if(change_email()){
			if(!v_new_email()){
				alert("请检查新的邮箱");
				return false;
			}else{
				if(!v_new_email_code()){
					alert("新邮箱验证码有误");
					return false;
				}
			}
		}
		if(!v_old_code()){
			alert("请检查当前邮箱验证码");
			return false;
		}
		return true;
	});
	
	is_edit_username.click(change_username_div);//点击事件的验证
	is_edit_email.click(change_email_div);
	
	get_old_email_vcode.click(send_old_email);
	get_new_email_code.click(send_new_email);
	
	vemail.blur(v_old_code);//旧邮箱的验证
	vemail.keyup(v_old_code);
	
	new_username.blur(v_new_username);//新用户名的验证
	new_username.keyup(v_new_username);	
	
	new_email.blur(v_new_email);//新邮箱的验证
	new_email.keyup(v_new_email);	
	
	vnemail.blur(v_new_email_code);//新邮箱验证码的验证
	vnemail.keyup(v_new_email_code);		
	
	function change_username(){
		if($(".edit_user_action").is(":hidden"))return false;
		else return true;
	}
	function change_email(){
		if($(".edit_email_action").is(":hidden"))return false;
		else return true;
	}
	function change_username_div(){
		if($(".edit_user_action").is(":hidden")){
			$(".edit_user_action").show();
		}else{
			$(".edit_user_action").hide();
		}
	}
	function change_email_div(){
		if($(".edit_email_action").is(":hidden")){
			$(".edit_email_action").show();
		}else{
			$(".edit_email_action").hide();
		}
	}	
	function send_old_email(){
		var old_email = $('input[name=oemail]').val();
		if(!change_username() && !change_email()){
			alert("请至少选择一个操作");
			return false;
		}
		$.get(SITE_URL + "tool/send_mail.php?type=vcode&email="+old_email,function(result){
			if(result=='true'){
				get_old_email_vcode.text("邮件已发送,请注意查收");
				get_old_email_vcode.attr('disabled',true);
				get_old_email_vcode.css("background-color","#CCC");
				return false;
			}else if(result=='error'){
				alert("表单有错误，目前无法修改，请联系管理员");
				return false;
			}else if(result=='false'){
				get_old_email_vcode.text("邮件发送失败，请联系管理员");
				alert("邮件发送失败，请联系管理员");
				return false;
			}
		});
	}
	function v_old_code(){
		var code = vemail.val();
		if(code.length!=10){
			$(".require").text("验证码长度必须为10位");
			$(".require").css("color","#F00");
			return false;
		}else{
			$(".require").text("长度符合要求");
			$(".require").css("color","#0F0");
		}
		var filter = /^[0-9A-Za-z]+$/;
		if(!filter.test(code)){
			$(".require").text("包含错误的值，请检查");
			$(".require").css("color","#F00");
			return false;
		}else{
			$(".require").text("符合验证码规则");
			$(".require").css("color","#0F0");
		}
		return true;
	}
	function v_new_username(){
		var a = new_username.val();
		var filter = /^[0-9A-Za-z]+$/;
		if(filter.test(a)){
			$.get(SITE_URL + "tool/check_info.php?type=username&value="+a,function(result){
				if(result=='true'){
					username_info.addClass("error");
					username_info.text("该用户名已经被使用");
					return false;
				}else if(result=='error'){
					username_info.addClass("error");
					username_info.text("该名称不符合规范");
					return false;
				}	
			});
			username_info.text("可以使用该用户名");
			username_info.removeClass("error");
			return true;
		}else{
			username_info.addClass("error");
			username_info.text("该名称不符合验证规范");
			return false;
		}
	}
	function v_new_email(){
		var a = new_email.val();
		var filter = /([a-zA-Z0-9]*[-_.]?[a-zA-Z0-9]+)+@([\w-]+\.)+[a-zA-Z]{2,}$/;
		if(filter.test(a)){
			$.get(SITE_URL + "tool/check_info.php?type=email&value="+a,function(result){
				if(result=='true'){
					new_email_info.text("该邮箱已经被注册");
					new_email_info.addClass("error");
					return false;
				}else if(result=='error'){
					new_email_info.text("该邮箱有误");
					new_email_info.addClass("error");
					return false;
				}	
			});
			new_email_info.text("该邮箱可以注册");
			new_email_info.removeClass("error");
			return true;
		}
		//if it's NOT valid
		else{
			new_email_info.text("这是一个错误的邮箱");
			new_email_info.addClass("error");
			return false;
		}
	}
	function v_new_email_code(){
		var code = vnemail.val();
		if(code.length!=10){
			vnemail_info.text("验证码长度必须为10位");
			vnemail_info.css("color","#F00");
			return false;
		}else{
			vnemail_info.text("长度符合要求");
			vnemail_info.css("color","#0F0");
		}
		var filter = /^[0-9A-Za-z]+$/;
		if(filter.test(code)){
			vnemail_info.text("符合验证码规则");
			vnemail_info.css("color","#0F0");			
		}else{
			vnemail_info.text("包含错误的值，请检查");
			vnemail_info.css("color","#F00");
			return false;
		}
		return true;
	}
	function send_new_email(){
		var email = new_email.val();
		if(!v_new_email()){
			alert("请先验证新的邮箱的正确性");
			return false;
		}
		$.get(SITE_URL + "tool/send_mail.php?type=vncode&email="+email,function(result){
			if(result=='true'){
				get_new_email_code.text("邮件已发送,请注意查收");
				get_new_email_code.attr('disabled',true);
				get_new_email_code.css("background-color","#CCC");
				return false;
			}else if(result=='error'){
				alert("表单有错误，目前无法修改，请联系管理员");
				return false;
			}else if(result=='false'){
				get_new_email_code.text("邮件发送失败，请联系管理员");
				alert("邮件发送失败，请联系管理员");
				return false;
			}
		});
		return true;
	}









});