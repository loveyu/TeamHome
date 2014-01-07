// JavaScript Document

/**
 *	中户修改密码验证
 */

$(document).ready(function(){
	//global vars
	var form = $("#user_change_pwd");
	var old_pwd = $("#old_pwd");
	var old_info = $("#old_info");
	var new_pwd = $("#new_pwd");
	var new_info = $("#new_info");
	var confirm_pwd = $("#confirm_pwd");
	var confirm_info = $("#confirm_info");
	
	
	//On blur
	old_pwd.blur(vOldPwd);
	new_pwd.blur(vNewPwd);
	confirm_pwd.blur(vConfirmPwd);
	
	//On key press
	old_pwd.keyup(vOldPwd);
	new_pwd.keyup(vNewPwd);
	confirm_pwd.keyup(vConfirmPwd);	
		
	//On Submitting
	form.submit(function(){

		if(vNewPwd() & vConfirmPwd())
			return true
		else
			return false;
	});
	
	//验证旧密码
	function vOldPwd(){
		if(old_pwd.val().length<8){
			old_info.addClass("error");
			old_info.text("旧密码不符合规则");
			return false;
		}else{
			old_info.removeClass("error");
			old_info.text("符合密码规则");
			return true;
		}
		return true;
	}
	function vNewPwd(){
		vOldPwd();
		if(new_pwd.val().length<8){
			new_info.addClass("error");
			new_info.text("新密码不符合规则");
			return false;
		}else{
			if(confirm_pwd.val().length>0){
				if(confirm_pwd.val()!=new_pwd.val()){
					new_info.addClass("error");
					new_info.text("两次密码不一致");
					return false;
				}else{
					new_info.removeClass("error");
					new_info.text("符合密码规则,且两次一致");
					return true;
				}
			}
			if(new_pwd.val()==old_pwd.val()){
				new_info.addClass("error");
				new_info.text("新密码不能和旧密码一样");
				return false;
			}
			new_info.removeClass("error");
			new_info.text("符合密码规则");
			return true;
		}
		return true;
	}
	
	function vConfirmPwd(){
		if(confirm_pwd.val().length<8){
			confirm_info.addClass("error");
			confirm_info.text("新密码不符合规则");
			return false;
		}else{
			if(confirm_pwd.val()!=new_pwd.val()){
				confirm_info.addClass("error");
				confirm_info.text("两次密码不一致");
				return false;
			}
			confirm_info.removeClass("error");
			confirm_info.text("符合密码规则,且两次一致");
			return true;
		}
		return true;
	}	
});
// JavaScript Document