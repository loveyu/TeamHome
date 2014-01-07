// JavaScript Document

/**
 *	登录界面表单验证
 */

$(document).ready(function(){
	//global vars
	var form = $("#loginForm");
	var username = $("#username");
	var usernameInfo = $("#usernameInfo");
	var password = $("#password");
	var passwordInfo = $("#passwordInfo");
	
	//On blur
	username.blur(validateUsername);
	password.blur(validatePassword);
	//On key press
	username.keyup(validateUsername);
	password.keyup(validatePassword);

	//On Submitting
	form.submit(function(){
		
		if(validateUsername() & validatePassword())
			return true
		else
			return false;
	});
	
	//validation functions
	function validateUsername(){
		//testing regular expression
		var a = $("#username").val();
		var filter = /([a-zA-Z0-9]*[-_.]?[a-zA-Z0-9]+)+@([\w-]+\.)+[a-zA-Z]{2,}$/;
		//if it's valid email
		if(filter.test(a)){
			username.removeClass("error");
			usernameInfo.text("邮箱符合规范");
			usernameInfo.removeClass("error");
			return true;
		}
		//if it's NOT valid
		else{
			username.addClass("error");
			usernameInfo.text("这是一个错误的邮箱");
			usernameInfo.addClass("error");
			return false;
		}
	}
	function validatePassword(){
		//if it's NOT valid
		if(password.val().length < 8){
			password.addClass("error");
			passwordInfo.text("密码至少是8个字符");
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
