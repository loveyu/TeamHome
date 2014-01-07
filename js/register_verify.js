// JavaScript Document

/**
 *	注册页面表单验证
 */

$(document).ready(function(){
	//global vars
	var form = $("#RegisterForm");
	var email = $("#email");
	var emailInfo = $("#emailInfo");
	var vcode = $("#vcode");
	var vcodeInfo = $("#vcodeInfo");
	
	//On blur
	email.blur(validateEmail);
	vcode.blur(validateCode);
	//On key press
	email.keyup(validateEmail);
	vcode.keyup(validateCode);
	
	vcodeInfo.click(SendMail);

	//On Submitting
	form.submit(function(){
		
		if(validateEmail() & validateCode())
			return true
		else
			return false;
	});
	
	//validation functions
	function validateEmail(){
		//testing regular expression
		var a = $("#email").val();
		var filter = /([a-zA-Z0-9]*[-_.]?[a-zA-Z0-9]+)+@([\w-]+\.)+[a-zA-Z]{2,}$/;
		//if it's valid email
		if(filter.test(a)){
			$.get(SITE_URL + "tool/check_info.php?type=email&value="+a,function(result){
				if(result=='true'){
					email.addClass("error");
					emailInfo.text("该邮箱已经被注册");
					emailInfo.addClass("error");
					return false;
				}else if(result=='error'){
					email.addClass("error");
					emailInfo.text("该邮箱有误");
					emailInfo.addClass("error");
					return false;
				}	
			});
			email.removeClass("error");
			emailInfo.text("该邮箱可以注册");
			emailInfo.removeClass("error");
			return true;
		}
		//if it's NOT valid
		else{
			email.addClass("error");
			emailInfo.text("这是一个错误的邮箱");
			emailInfo.addClass("error");
			return false;
		}
	}
	function SendMail(){
		if(!validateEmail()){
			alert("请先验证邮箱再正确性再注册");
			return false;
		}
		var a = email.val();
		$.get(SITE_URL + "tool/send_mail.php?type=register&email="+a,function(result){
				if(result=='error'){
					vcodeInfo.text("发送邮件出现错误,再试");
					vcodeInfo.addClass("error");
					return false;
				}else if(result=='false'){
					vcodeInfo.text("邮件发送错误");
					vcodeInfo.addClass("error");
					return false;
				}
				vcodeInfo.removeClass("error");
				vcodeInfo.css("color","#666");
				vcodeInfo.text("邮件已发送请查收");
				vcodeInfo.attr('disabled',true);
				return true;
		});
	}
	
	function validateCode(){
		//if it's NOT valid
		if(vcode.val().length < 10){
			password.addClass("error");
			passwordInfo.text("验证码至少是10位字符");
			passwordInfo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			password.removeClass("error");
			passwordInfo.text("密码符合规范");
			passwordInfo.removeClass("error");
			return true;
		}
	}
});
