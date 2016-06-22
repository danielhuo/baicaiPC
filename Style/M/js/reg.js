var arrBox = new Array();
arrBox["dvphone"] = "<i class='fa fa-exclamation-circle'></i> 请填写真实的手机号码。";
arrBox["dvUser"] = "<i class='fa fa-exclamation-circle'></i> 输入6-20个字母、数字、汉字、下划线";
arrBox["dvPwd"] = "<i class='fa fa-exclamation-circle'></i> 输入6-16个字母、数字、下划线。";
arrBox["dvRepwd"] = "<i class='fa fa-exclamation-circle'></i> 请再一次输入您的密码。";
arrBox["dvRec"] = "<i class='fa fa-exclamation-circle'></i> 推荐人用户名或手机号,可不填";
arrBox["dvCode"] = "<i class='fa fa-exclamation-circle'></i> 请输入六位验证码。";

var arrWrong = new Array();
arrWrong["dvphone"] = "<i class='fa fa-minus-circle'></i> 请输入真实的手机号码。";
arrWrong["dvUser"] = "<i class='fa fa-minus-circle'></i> 6-20个字母、数字、汉字、下划线。";
arrWrong["dvPwd"] = "<i class='fa fa-minus-circle'></i> 6-16个字母、数字、下划线。";
arrWrong["dvRepwd"] = "<i class='fa fa-minus-circle'></i> 两次密码输入不一致";
arrWrong["dvRec"] = "<i class='fa fa-minus-circle'></i> 输入推荐人用户名或手机号";
arrWrong["dvCode"] = "<i class='fa fa-minus-circle'></i> 验证码位数输入不正确。";

var arrOk = new Array();
arrOk["dvphone"] = "<i class='fa fa-check-circle'></i> 手机号码可用。";
arrOk["dvUser"] = "<i class='fa fa-check-circle'></i> 用户名可用。";
arrOk["dvPwd"] = "<i class='fa fa-check-circle'></i> 密码格式正确。";
arrOk["dvRepwd"] = "<i class='fa fa-check-circle'></i> 密码格式正确。";
arrOk["dvRec"] = "<i class='fa fa-check-circle'></i> 推荐人正确。";
arrOk["dvCode"] = "<i class='fa fa-check-circle'></i> 验证码位数正确。";


function Init() {

    $('#txtphone').blur(function() { Blurphone(); });
    $('#txtUser').blur(function() { BlurUName(); });
    $('#txtPwd').blur(function() { BlurPwd(); });
    $('#txtRepwd').blur(function() { BlurRepwd(); });
	$('#txtRec').blur(function() { BlurRec(); });
    //$('#txtCode').blur(function() { BlurCode(); });

}

$(document).ready(
function() {
    Init();
    $("#txtEmail").focus();
    //$("#Img1").click(function() { RegSubmit(this); });
    $("#txtCode").keypress(
    function(e) {
        if (e.keyCode == "13")
            $("#Img1").click();
    });
});

function strLength(as_str){
		return as_str.replace(/[^\x00-\xff]/g, 'xx').length;
}
function isLegal(str){
	if(/[!,#,$,%,^,&,*,?,~,\s+]/gi.test(str)) return false;
	return true;
}
function BlurUName() {
    var txt = "#txtUser";
    var td = "#dvUser";
    var pat = new RegExp("^[\\d|\\.a-z_A-Z|\\u4e00-\\u9fa5|\\x00-\\xff]$", "g");
    var str = $(txt).val();
    var strlen = strLength(str);
    if (isLegal(str) && strlen>=6 && strlen<=20) {
        $(td).html(GetP("reg_info", "<img style='margin:2px;' src='"+imgpath+"images/zhuce0.gif'/>&nbsp;正在检测用户名……"));
        $.ajax({
            type: "post",
            async: false,
            url: "/member/common/ckuser/",
			dataType: "json",
            data: {"UserName":str},
            timeout: 3000,
            success: AsyncUname
        });
    }
    else {
        $(td).html(GetP("reg_wrong", arrWrong["dvUser"]));
    }
}
function BlurRec() {
    var txt = "#txtRec";
    var td = "#dvRec";
    var pat = new RegExp("^[a-zA-Z0-9_]*$", "g");
    var str = $(txt).val();
	
    var strlen = strLength(str);
	if (isLegal(str) && strlen>=3 && strlen<=20) {
		$(td).html(GetP("reg_info", "<img style='margin:2px;' src='"+imgpath+"images/zhuce0.gif'/>&nbsp;正在检测推荐人……"));
		$.ajax({
			type: "post",
			async: false,
			url: "/member/common/ckInviteUser/",
			dataType: "json",
			data: {"InviteUserName":str},
			timeout: 3000,
			success: AsyncInviteUname
		}
		);
	}else if(str==''){
		$(td).empty();
    }
    else {
        $(td).html(GetP("reg_wrong", arrWrong["dvRec"]));
    }
}
function AsyncUname(data) {
    if (data.status == "1") {
        $("#dvUser").html(GetP("reg_ok", arrOk["dvUser"]));
    }
    else {
        $("#dvUser").html(GetP("reg_wrong", "<i class='fa fa-minus-circle'></i> 此用户名已被注册。"));

    }

}
function AsyncInviteUname(data) {
    if (data.status == "1") {
        $("#dvRec").html(GetP("reg_ok", arrOk["dvRec"]));
    }
    else {
        $("#dvRec").html(GetP("reg_wrong", "<i class='fa fa-minus-circle'></i> 推荐人不存在。"));

    }

}
function Blurphone() {
    var txt = "#txtphone";
    var td = "#dvphone";
   var mbTest = /^(13|14|15|17|18)[0-9]{9}$/;
    var str = $(txt).val();
    if (mbTest.test(str)) {
        $(td).html(GetP("reg_info", "<img style='margin:2px;' src='"+imgpath+"images/zhuce0.gif'/>&nbsp;正在检测手机号码……"));
        $.ajax({
            type: "post",
            async: false,
			dataType: "json",
            url: "/member/common/ckphone/",
            data: {"phone":str},
            timeout: 3000,
            success: Asyncphone
        });
    }
    else { $(td).html(GetP("reg_wrong", arrWrong["dvphone"])); }
}

function Asyncphone(data) {
    if (data.status == "1") {
        $("#dvphone").html(GetP("reg_ok", arrOk["dvphone"]));
    }
     else {
       // $("#dvEmail").html(GetP("reg_wrong", "<img style='margin:2px;' src='"+imgpath+"images/zhuce2.gif'/>&nbsp;邮箱已经在本站注册<a href='javascript:;' onlick='getPassWord();'>取回密码？</a>"));
		$("#dvphone").html(GetP("reg_wrong", "<i class='fa fa-minus-circle'></i> 该号码已被注册"));
    }
}

//function getPassWord() {
//	window.location.href = "/member/common/getpassword/";
//}

function BlurPwd() {
    var txt = "#txtPwd";
    var td = "#dvPwd";
    var pat = new RegExp("^.{6,20}$", "i");
    var str = $(txt).val();
    if (pat.test(str)) {
        //格式正确
        $(td).html(GetP("reg_ok", arrOk["dvPwd"]));
    }
    else {
        $(td).html(GetP("reg_wrong", arrWrong["dvPwd"]));
    }
}

function BlurRepwd() {
    var txt = "#txtRepwd";
    var td = "#dvRepwd";
    var str = $(txt).val();
    if (str == $("#txtPwd").val() && str.length > 5) {
        //格式正确
        $(td).html(GetP("reg_ok", arrOk["dvRepwd"]));
    }
    else {
        $(td).html(GetP("reg_wrong", arrWrong["dvRepwd"]));
    }
}
//检验 验证码
function BlurCode() {
    var txt = "#txtCode";
    var td = "#dvCode";
    var pat = new RegExp("^[\\da-z]{4}$", "i");
    var str = $(txt).val();
    if (pat.test(str)) {
        //格式正确
        $.post("/member/common/ckcode/", { Action: "post", Cmd: "CheckVerCode", sVerCode: str }, AsyncVerCode);
    }
    else {
        $(td).html(GetP("reg_wrong", arrWrong["dvCode"]));
    }
}

function AsyncVerCode(data) {
    if (data == "1") {
        $("#dvCode").html(GetP("reg_ok", arrOk["dvCode"]));
    }
    else {
	//$("#dvCode").html(GetP("reg_ok", arrOk["dvCode"]));
        $("#dvCode").html(GetP("reg_wrong", "<i class='fa fa-minus-circle'></i> 验证码错误！"));
		//$("#dvCode").html(GetP("reg_wrong", arrBox["dvCode"]));
    }
}

function ClickBox(id) {
    var ele = '#' + id;
    $(ele).html(GetP("reg_info", arrBox[id]));
}

function GetP(clsName, c) { return "<div class='" + clsName + "'>" + c + "</div>"; }

function RegSubmit(ctrl) {
    $(ctrl).unbind("click");
    var arrTds = new Array("#dvphone","#dvUser", "#dvPwd","#dvRepwd", "#dvCode", "#dvRec");
    Blurphone();
    BlurUName();
    BlurPwd();
	BlurRec();
    BlurCode();
    for (var i = 0; i < arrTds.length; i++) {
        if ($(arrTds[i]).html().indexOf('reg_wrong') > -1) {
            $(ctrl).click(function() { RegSubmit(this); });
            return false;
        }
    }
	
	var check = $("input[type='checkbox']").attr("checked");
	if(!check){
		$.jBox.tip("请确认服务协议");  
		return false;
  	}

	//$.jBox.tip("提交中......","loading");
	$.ajax({
		url: curpath+"/regtemp/",
		data: {"txtEmail": $("#txtEmail").val(),"txtUser": $("#txtUser").val(),"txtPwd": $("#txtPwd").val(),"sVerCode": $("#txtCode").val(),"txtRec": $("#txtRec").val()},
		//timeout: 8000,
		cache: false,
		type: "post",
		dataType: "json",
		success: function (d, s, r) {
			if(d){
				if(d.status==0){
					$.jBox.tip(d.message,"fail");
					//$(ctrl).click(function() { RegSubmit(this); });
				}else{
					window.location.href="/member/common/register2/";
					//window.location.href="/member/";//临时修改
				}
			}
		}
	});
}

function myrefresh()
{
	   window.location.href="/member/";
}
function AsyncReg(data) {
    Close_Dialog_AutoClose();
    if (data == "True") {
        suc();
    }
    else { }
}

function AsyncReg_Back() { window.location.href = "/member/"; }