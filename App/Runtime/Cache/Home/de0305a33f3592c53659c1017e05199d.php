<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="keywords" content="<?php echo ($glo["web_keywords"]); ?>">
	<meta name="description" content="<?php echo ($glo["web_descript"]); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="__ROOT__/Style/H/css/css.css" />
	<link type="text/css" rel="stylesheet" href="/Style/bootstrap-3.3.5/css/bootstrap.min.css"/>
	
	<link type="text/css" rel="stylesheet" href="__ROOT__/Style/JBox/Skins/Currently/jbox.css"/>
	<link href="__ROOT__/Style/H/css/Mbmber.css" rel="stylesheet" type="text/css"/>
	
	<link type="text/css" rel="stylesheet" href="/Style/Font-Awesome/css/font-awesome.min.css"/>
	<script language=javascript type="text/javascript" src="__ROOT__/Style/Js/jquery.js"></script>
	<script language=javascript src="__ROOT__/Style/JBox/jquery.jBox.min.js" type=text/javascript></script>
	<script language=javascript src="__ROOT__/Style/JBox/jquery.jBoxConfig.js" type=text/javascript></script>
	<script  type="text/javascript" src="__ROOT__/Style/Js/ui.core.js"></script>
	<script  type="text/javascript" src="__ROOT__/Style/Js/ui.tabs.js"></script>
	<script type="text/javascript" src="__ROOT__/Style/Js/utils.js"></script>
	<script type="text/javascript">
		function makevar(v){
			var d={};
			for(i in v){
				var id = v[i];
				d[id] = $("#"+id).val();
				if(!d[id]) d[id] = $("input[name='"+id+"']:checked").val();
			}
			return d;
		}

		function ajaxGetData(url,targetid,data){
				if(!url) return;
				data = data||{};
				var thtml = '<div class="loding"><img src="__ROOT__/Style/Js/006.gif"align="absmiddle" />　信息正在加载中...,如长时间未加载完成，请刷新页面</div>';
				$("#"+targetid).html(thtml);
				
				$.ajax({
					url: url,
					data: data,
					timeout: 10000,
					cache: true,
					type: "get",
					dataType: "json",
					success: function (d, s, r) {
						if(d) $("#"+targetid).html(d.html);
					},
					error: '',
					complete: ''
				});
			
		}
		var currentUrl = window.location.href.toLowerCase();
		$(document).ready(function() {
			$('#rotate > ul').tabs();/* 第一个TAB渐隐渐现（{ fx: { opacity: 'toggle' } }），第二个TAB是变换时间（'rotate', 2000） */
			$('.dw_navlist li a').click(function() { // 绑定单击事件
				var nowurl = $(this).attr('href');
				var vid = nowurl.split("#");
				try{
					if(currentUrl.indexOf(vid[0]) != -1 ){
						$('#rotate > ul').tabs('select', "#"+vid[1]); // 切换到第三个选项卡标签
						var geturl= $('#rotate > ul li a [href="#'+vid[1]+'"]').attr("ajax_href");
						ajaxGetData(geturl,vid[1]);
						return false;
					}
				}catch(ex){};
					return true;
			});
			
			$('.ajaxdata a').click(function(){
				var geturl = $(this).attr('ajax_href');
				var hasget = $(this).attr('get')||0;
				var nowurl = $(this).attr('href');
				var vid = nowurl.split("#");
				if(hasget!=1) ajaxGetData(geturl,vid[1]);
				$(this).attr('get','1');
				$('html,body').animate({scorllTop:0},1000);
				return false;
			})
		});
		//ui
	    function addBookmark(title, url) {
	        if (window.sidebar) {
	            window.sidebar.addPanel(title, url, "");
	        }
	        else if (document.all) {
	            window.external.AddFavorite(url, title);
	        }
	        else if (window.opera && window.print) {
	            return true;
	        }
	    }
	    function SetHome(obj, vrl) {
	        try {
	            obj.style.behavior = 'url(#default#homepage)'; obj.setHomePage(vrl);
	            NavClickStat(1);
	        }
	        catch (e) {
	            if (window.netscape) {
	                try {
	                    netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
	                }
	                catch (e) {
	                    alert("抱歉！您的浏览器不支持直接设为首页。请在浏览器地址栏输入“about:config”并回车然后将[signed.applets.codebase_principal_support]设置为“true”，点击“加入收藏”后忽略安全提示，即可设置成功。");
	                }
	                var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
	                prefs.setCharPref('browser.startup.homepage', vrl);
	            }
	        }
	    }
	        $(function() {
	            $(".dw_navlist li,.dv_r_5 li").mousemove(function() {
	                $(this).addClass("current");
	            }).mouseout(function() {
	                $(this).removeClass("current");
	            });
	        });
	</script>

<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<title><?php echo ($bill["name"]); ?>-<?php echo ($glo["web_name"]); ?></title>
<meta name="keywords" content="<?php echo ($glo["web_keywords"]); ?>" />
<meta name="description" content="<?php echo ($glo["web_descript"]); ?>" />
<link type="text/css" rel="stylesheet" href="/Style/Mobile/css/dataTab.css"/>
<link rel="stylesheet" type="text/css" href="__ROOT__/Style/fancybox/jquery.fancybox-1.3.2.css" media="screen" />
<script type=text/javascript><!--//--><![CDATA[//><!--
function menuFix() {
var ele_ = document.getElementById("nav");
	if(!ele_) return;
var sfEls = ele_.getElementsByTagName("li");
for (var i=0; i<sfEls.length; i++) {
sfEls[i].onmouseover=function() {
this.className+=(this.className.length>0? " ": "") + "sfhover";
}
sfEls[i].onMouseDown=function() {
this.className+=(this.className.length>0? " ": "") + "sfhover";
}
sfEls[i].onMouseUp=function() {
this.className+=(this.className.length>0? " ": "") + "sfhover";
}
sfEls[i].onmouseout=function() {
this.className=this.className.replace(new RegExp("( ?|^)sfhover\\b"),
"");
}
}
}
window.onload=menuFix;
//--><!]]>
</script>
<link rel="stylesheet" type="text/css" href="__ROOT__/Style/H/css/home.css" />
</head><body>
<div class="top-box">
	<div class="container">
		<div class="row top">
			<div class="col-md-6 col-xs-6" style="color:#fff;line-height:30px;">
				<span class="glyphicon glyphicon-phone-alt"></span>
				0512-62853600
			</div>
			<div class="col-md-6 col-xs-6 top-navbar top-navbar-right">
				<ul id="erji">
						<?php if($_SESSION['u_id']): ?><li>
							<a  href="__APP__/member/" style="color:#2AA198">您好，<?php echo session('u_user_name');?></a>
						</li>
						<li>
							<a  href="__APP__/member/msg#fragment-1">
								<span class="glyphicon glyphicon-envelope" style="color:#ff9900;top:3px;"></span>
								<span class="badge"><?php echo (($unread)?($unread):0); ?></span>
							</a>
						</li>		    
						<li><a href="__APP__/member/common/actlogout" class="btn btn-danger">退出</a></li>
						
						<?php else: ?> 
						<li >
							<a href="__ROOT__/member/common/login/" class="btn btn-info">立即登录</a>
						</li>
						<li class="">
							<a href="__ROOT__/member/common/regist/" class="btn btn-danger">免费注册</a>
						</li><?php endif; ?>
					</ul>
			</div>
		</div>
	</div>
</div>
<div class="head-box bg-white">
	<div class="container">
		<nav class="navbar navbar-default row" role="navigation">
			<div class="navbar-header N_logo">
				<button type="button" class="navbar-toggle" data-toggle="collapse" 
		         data-target="#example-navbar-collapse">
		         <span class="sr-only">切换导航</span>
		         <span class="icon-bar"></span>
		         <span class="icon-bar"></span>
		         <span class="icon-bar"></span>
		        </button>
			 	<a href="/"><?php echo get_ad(1);?></a>
			</div>
		   <div class="collapse navbar-collapse" id="example-navbar-collapse">
		      <ul class="nav navbar-nav navbar-right navigation-list" id="dw_ul">
		         <?php $typelist = getTypeList(array('type_id'=>0,'limit'=>9)); foreach($typelist as $vtype=> $va){ ?>
							  <li class="navigation-item "> <a href="<?php echo ($va["turl"]); ?>" class="navigation-item-name"><?php echo ($va["type_name"]); ?>
								<?php $sontypelist = getTypeList(array('type_id'=>$va['id'],'limit'=>8,'notself'=>true)); if($sontypelist != null){ ?>
								<?php } ?>
								</a>
							  </li>
							  <?php } ?>
		      </ul>
		   </div>
		</nav>
	</div>
</div>

<style type="text/css">

#toinvest{
	text-align:center;
    width: 150px;
	margin:0 auto;
	font-size:20px;
	font-weight:bold;
    background-color:#A1E8FA;
	color:white;
    height:40px;	
	line-height:40px;
	cursor:pointer;
}

#first{
	display:none;
	position:absolute;
	left:50%;
	top:50%;
	background-color:white;
	width:500px;
	height:300px;
	margin:-150px -250px;
	border:1px solid lightblue;
	padding:10px  30px;
	z-index:200;

}
#first table{
	width:100%;
	height:100%;
	text-align:center;

}
#first table th,#first table td{
	width:50%;
	border-bottom:1px dotted lightblue;
}
#first table th{	
	font-weight:bold;
	font-size:16px;
}
#doinvest{
	width:50%;
	margin-left:25%;
	text-align:center;
	background-color:green;
	color:white;
	box-shadow:1px 1px 1px 1px gray;
	
}
.lose{
	width:50%;
	margin-left:25%;
	text-align:center;
	background-color:red;
	color:white;
	box-shadow:1px 1px 1px 1px gray;
	
}

#doinvest:hover{
	box-shadow:none;
	
}

.invest img{
	position: absolute;
	top:-30px;
	left: 0px;
	z-index: 1;
	width: 200px;
	height: 60px;
}

.invest{
	position:relative;	
	padding:20px 0;
	margin-top:50px;
	background-color:#E2F7FC;
}

.invest table{
	text-align: center;
	line-height: 40px;
	font-size: 16px;
	padding-top: 10px;
	width: 980px;
	margin: 10px auto;	
	border-bottom: 2px dashed #E2E2E2;
	width:980px;
}
.invest table td{
	width:50px;	
}
.invest table td[rowspan="2"]{
	width:20px;
}

.intro1{text-align:center;}
.intro1 table{
	text-align: center;
	line-height: 25px;
	font-size: 16px;
	padding-top: 5px;
	width: 980px;
	margin: 10px auto;	
	width:980px;
}
.intro1 table td{
	width:315px;
}
.switch{position: absolute;
	top:-30px;
	left: 0px;
	z-index: 1;
	width: 1000px;
	height: 60px;background:url(/Style/H/images/rec.png);}
.switch div{height:100%;width:50%;float:left;}
</style>
<script>
	$(function(){
		
	    if(<?php echo ($bill["status"]); ?> != 2){
			$("#table2").hide();
		}
		
		$('.intro1:eq(1)').hide();
		
		$(".left").click(function(){
			$('.intro1:eq(0)').show().siblings().hide();
		});
		$(".right").click(function(){
			$('.intro1:eq(1)').show().siblings().hide();
		});
		
		
		
		
		$("#toinvest").click(function(){
		
			$login=<?php echo $_SESSION['u_id']?$_SESSION['u_id']:0;?>;
		
			if(!$login){
				$.jBox.tip("请先登录");
	            window.location.href="__APP__/member/common/login";
			}else{
				 $.ajax({
					  url: "__URL__/investcheck",
					  dataType: "json",
					  success: function(d) {	 
							  if (d.status == 1) {
									$.jBox.tip("请先登录");
	                                 window.location.href="__APP__/member/common/login";
							  }
							  else if(d.status == 2)// 无担保贷款多次提醒
							  {
									$.jBox.tip("请先手机认证");
	                                window.location.href="__APP__/member/verify?id=1#fragment-2";
								
							  }
							  else if(d.status == 3)// 无担保贷款多次提醒
							  {
								    $.jBox.tip("请先实名认证");
								    window.location.href="__APP__/member/verify?id=1#fragment-3";
							  }else{
							    $("#first").show();
							  $("#plus").text(d.message);
								 // $.jBox.tip(d.message);  
							  }
					  }
				  });
			
			}
		
		});
		$("#doinvest").click(function(){
				 var pin = $("#pin").val();				// 支付密码
		         var money = $("#enter_value").val();		// 输入投资金额
		         var id = <?php echo ($bill["id"]); ?>;	
				 var total=$("#plus").val();
				 
			 // 投标编号
				if(!money){
				$.jBox.tip("请输入购买金额");  
							return false;
				}
				
			
               var re = /^[1-9]\d*$/;  
					 if (!re.test(money))  
				   {  
				//alert("请输入正整数");  
				 $.jBox.tip("请输入正整数");  
				return false; ;
				   }
				
				if(!pin){
					$.jBox.tip("请输入支付密码");  
					return false;
				}
			
				  $.ajax({
					  url: "__URL__/toinvest",
					  type: "post",
					  dataType: "json",
					  async: false,
					  data: {"money":money,'pin':pin,'id':id,'total':total},
					  success: function(d) {
							
							  if (d.status == 1) {
								//	investmoney = money;
								window.location.href="__APP__/member/user#fragment-1";
							  var content = d.message;
								$.jBox(content, {title:'会员投标提示',buttons: {}});
								
							  }
							  else if(d.status == 2)// 无担保贷款多次提醒
							  {
								  var content = d.message;
									$.jBox(content, {title:'会员投标提示',buttons: {}});
							  }
							   else if(d.status == 3)
							  {
								  var content = '<div class="jbox-custom"><p>'+ d.message +'</p><div class="jbox-custom-button" style="color:#de002a"><span onclick="$.jBox.close()">取消</span><span onclick="ischarge(true)">去充值</span></div></div>';
								  
									$.jBox(content, {title:'会员投标提示',buttons: {}});
							  }
							  else if(d.status == 4)
							  {
								  $.jBox.tip(d.message);
							  }else if(d.status == 5)
							  {
								  $.jBox.tip(d.message);  
							  }else if(d.status==6)
							  {
							   $.jBox.tip(d.message);
							  }
							  else{
							  $("#first").hide();
							  window.location.href=window.location.href;
							   $.jBox.tip(d.message);  
							  }
					  }
				  });
			})
			
			
			
	})



 
function lose(){
	$("#first").hide();
}
function ischarge(d){
	if(d===true) location.href='/member/charge#fragment-1';
}	
</script>
<div class="main">
		<div id="first" >
	  <table >
	  <tr>
	  <th>可用余额:</th><td><span id="plus"></span></td>
	  </tr>
	  <tr>
	  <th>投资金额:</th><td><input type="text" name="money" id="enter_value"/></td>
	  </tr>
	  <tr>
	  <th>支付密码:</th><td><input type="password" id="pin"/></td>
	  </tr>
	  <tr>
	  <td><input type="button" value="取消投标"  class="lose" onclick="lose();" /></td>
	  <td ><input type="button" value="确定投标" id="doinvest" /></td>
	  </tr>
	  </table>
			
				
	</div>
		
	<div class="invest">	
		<img src="__ROOT__/Style/H/images/pbb.png"></img>
		<table>
			<tr>
				<td rowspan="2" style="font-weight:bold;font-size:20px;">JK<?php echo ($bill["id"]); ?></td>
				<td style="width:40px;">年利率</td><td>借款金额</td><td>还需资金</td><td>还款期限</td>
			</tr>
			<tr>
				<td style="font-size:25px;color:#AEE6F3;width:40px;"><?php echo ($bill["interest_rate"]); ?>%</td>
				<td><?php echo (num_format($bill["amount"])); ?>元</td>
				<td class="red"><?php echo (num_format($bill["need"])); ?>元</td>
				<td><?php echo ($bill["invest_duration"]); ?>天</td>
			</tr>
		</table>
		
		<table id="table2" style="border:none;">
			<tr>
				<td>剩余时间 :</td>
				<td id="loan_time" class="red">-- 天 -- 小时 -- 分 -- 秒</td>
				<td rowspan="2"><div id="toinvest">立即投标</div></td>
			</tr>
			<tr>
				<td>投标金额 :</td><td>一元起投</td>
			</tr>
		
		</table>
	</div >
	
	
	
	
	<div style="margin-top:50px;position:relative;" class="div2">
		<div class="switch">
			<div class="left"></div>
			<div class="right"></div>
		</div>
		
		
		
		<div style="padding:50px 20px;height:820px;background-color:#E2F7FC;">
			<div class="intro1">
				<div style="font-size:16px;color:#2AA198;text-align:left;font-weight:bold;">
					<span style="color:#DE002A">本产品安全保障:</span>
				银行承兑汇票到期银行无条件兑付<br/>
				<span style="color:#DE002A">唯一风险:</span>银行倒闭
				</div>
				<h3 style="text-align:left;font-size:16px;font-weight:bold;margin-top:30px;color:#DE002A">项目票据展示:</h3>
				<img src="__ROOT__/<?php echo ($bill["bill_img"]); ?>" style="width:100%;margin-top:10px;margin-bottom:20px;">
				
				<div style="font-size:16px;line-height:30px;text-align:left;margin-top:30px;">
					<h3 style="text-align:left;color:#DE002A;">备注:</h3>
					<p style="color:#2AA198;font-weight:bold;">
						1、银行承兑汇票是出票人在承兑银行开立的，由承兑银行保证在指定日期无条件支付确定金额给收款人或者持票人的票据。在票小宝平台的理财模式中，质押物为银行承兑汇票，持票企业将票据背书质押至票小宝的合作托管银行，并由票小宝发布票据理财标的进行融资，票据到期后，由银行托收对付。<br/>
2、根据《票据法》相关规定：银行承兑汇票由银行承兑，银行承诺到期无条件兑付该票据金额给予该银行承兑汇票的所有人。
					
					</p>

				</div>
			</div>
			<div class="intro1">
				<table>
					 <tr>
						<th>投资人</th><th>投资时间</th><th>投资金额</th>
					</tr>
					<?php if(is_array($list["list"])): $i = 0; $__LIST__ = $list["list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vb): $mod = ($i % 2 );++$i;?><tr>
						<td><?php echo (hidecard($vb["user_name"],4)); ?></td><td><?php echo (date("Y/m/d",$vb["invest_time"])); ?></td><td><?php echo (num_format($vb["invest_amount"])); ?></td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?> 
						
				</table>
				<div class="list_bottom">
					<div class="list_bottom_right">
						<ul>
						<?php echo ($list["page"]); ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>
      
      
	  
<script type="text/javascript">
	var seconds;
	var pers = <?php echo (($bill["progress"])?($bill["progress"]):0); ?>/100;
	
	var timer=null;
	if (pers >= 1) {
		$("#loan_time").html("投标已经结束！");
	}else{
		setLeftTime();
	}
	function setLeftTime() {
		seconds = parseInt(<?php echo ($bill["lefttime"]); ?>, 10);
		timer = setInterval(showSeconds,1000);
	}
	
	function showSeconds() {
		var day1 = Math.floor(seconds / (60 * 60 * 24));
		var hour = Math.floor((seconds - day1 * 24 * 60 * 60) / 3600);
		var minute = Math.floor((seconds - day1 * 24 * 60 * 60 - hour * 3600) / 60);
		var second = Math.floor(seconds - day1 * 24 * 60 * 60 - hour * 3600 - minute * 60);
		if (day1 < 0) {
			clearInterval(timer);
			$("#loan_time").html("投标已经结束！");
		} else if (pers >= 1) {
			clearInterval(timer);
			$("#loan_time").html("投标已经结束！");
		} else {
			$("#loan_time").html(day1 + " 天 " + hour + " 小时 " + minute + " 分 " + second + " 秒");
		}
		seconds--;
	}                
	

</script>
				
		  
          
﻿<style type="text/css">
    .menubar
        {line-height: 25px;}
    
    .menuitem
        { position: relative; float: left; z-index:9999;}
    .menuitem .submenu
        {background: #FFFFFF; border: 1px solid #E7EAEC; position: absolute;width:9.0em;margin-top:-50px;margin-left:-110px;}
    .menuitem .submenu
        {display: none;}
    .menuitem:hover .submenu
        {display: block;}
</style>
<div  style="position: fixed; bottom: 150px; right: 0px; width:50px ; z-index:9999;">
	<div class="menubar">
	    <div class="menuitem"style="border:none;height:auto;background:none;">
	        <div >
			<img src="__ROOT__/Style/A/images/jixiangwu.png"  style="height:50px;width:50px;"/>
			  
			</div>
	    </div>	
	</div>
	<div class="menubar">
	    <div class="menuitem" >
	        <div style="margin-top:5px;">
	        	<img src="__ROOT__/Style/A/images/qqq.png"  style="height:50px;width:50px;"/>
	        </div>
	        <div class="submenu" >
				<div style="font-size:10pt;height:9.5px;">
					<a class="icoTc" href="http://wpa.qq.com/msgrd?v=3&uin=3083483550&site=qq&menu=yes" target="_blank">客服-白白</a>
				</div><br>
				<div style="font-size:10pt;border-top:#E7EAEC solid 1px;height:9.5px;">
					<a class="icoTc" href="http://wpa.qq.com/msgrd?v=3&uin=3111795998&site=qq&menu=yes" target="_blank">销售-菜菜</a>
				</div><br>
				<div style="font-size:10pt;border-top:#E7EAEC solid 1px;height:9.5px;">
					<a class="icoTc" href="http://wpa.qq.com/msgrd?v=3&uin=2293473403&site=qq&menu=yes" target="_blank">销售-百百</a>
				</div><br>
				<div style="font-size:10pt;border-top:#E7EAEC solid 1px;height:9.5px;">
					<a class="icoTc" href="http://wpa.qq.com/msgrd?v=3&uin=641172504&site=qq&menu=yes" target="_blank">客服-财财</a>
				</div>
	        </div>
	    </div>
	   
	</div>
	<div class="menubar" >
	    <div class="menuitem">
	        <div style="margin-top:5px;">
	        	<img src="__ROOT__/Style/A/images/p.png" style="height:50px;width:50px;"/>
	        </div>
	        <div class="submenu" >
	            <div style="height:50px;font-size:10pt; padding-bottom:20px;"><br>0512-62853600</div>
	        </div>
	    </div>
	    
	</div>

	<div class="menubar">
	 	<div class="menuitem">
	     	<div style="margin-top:5px;">
	     		<img src="__ROOT__/Style/A/images/share.jpg" style="height:50px;width:50px;"/>
	     	</div>
			<div class="submenu" >
		        <div style="height:20px"><img title="QQ空间" src="__ROOT__/Style/A/images/share.png" onclick="linkto(1);" class="img1"
				style="margin-left:30px;margin-top:-25px;position:absolute;clip:rect(30px 40px 60px 10px);"
				/></div><br>
				<div style="font-size:10pt;border-top:#E7EAEC solid 1px;height:20px"><img class="img2" title="人人网"  src="__ROOT__/Style/A/images/share.png" onclick="linkto(2);" class="img2"
				style="margin-left:-25px;margin-top:-25px;position:absolute;clip:rect(30px 95px 60px 70px);"
				/></div><br>
				<div style="font-size:10pt;border-top:#E7EAEC solid 1px;height:20px"><img title="新浪微博" src="__ROOT__/Style/A/images/share.png" onclick="linkto(3);" class="img3"
				style="margin-left:-80px;margin-top:-25px;position:absolute;clip:rect(30px 160px 60px
				124px);"
				/></div><br>
				<div style="font-size:10pt;border-top:#E7EAEC solid 1px;height:20px"><img title="腾讯微博" src="__ROOT__/Style/A/images/share.png" onclick="linkto(4);" class="img4"
				style="margin-left:-140px;margin-top:-25px;position:absolute;clip:rect(30px 205px 60px 184px);"
				/></div><br>
				
	        </div>
	    </div>
	   
	</div>
	<div class="menubar">
	    <div style="z-index:9999;float:right;position:fixed; bottom: 95px;right:0px;">
			<img src="__ROOT__/Style/H/images/top.png"  id="btt" style="height:50px;width:50px;margin-top:5px;"/>
	    </div>	
	</div>
</div>
<script>		
	$(
		function(){
		    //获取视口高度
			var cHeight=$(window).height();
			var sTop=0;
			var $_btt=$('#btt');
			var timer=null;
			var isStop=false;
			 $_btt.fadeOut(500);
			//滚动条滚动事件响应
			$(window).scroll(
				function(){
				    //获取滚动条的垂直位置
					sTop=$(window).scrollTop();
                 if(isStop==true||sTop==0) 
								clearInterval(timer);				
					if(sTop>cHeight)
						$_btt.fadeIn(500);
					else $_btt.fadeOut(500);
					isStop=true;					
				}
			);
			
			//回到顶部按钮点击事件响应
			$_btt.click(
				function(){
				    isStop=false;
					timer=setInterval(function(){												 		
					        $(window).scrollTop(0);						
							isStop=false;
				          },50);
			    }				
			);
			
			$(".img1").mouseover(
				function(){
				   $(".img1").css({"clip":"rect(70px 40px 100px 10px)",
				   "margin-top":"-60px"});
				}
			);
			$(".img1").mouseout(
				function(){
				   $(".img1").css({"clip":"rect(30px 40px 60px 10px)",
				   "margin-top":"-25px"});
				}
			);
			
				$(".img2").mouseover(
				function(){
				   $(".img2").css({"clip":"rect(70px 95px 100px 70px)",
				   "margin-top":"-60px"});
				}
			);
			$(".img2").mouseout(
				function(){
				   $(".img2").css({"clip":"rect(30px 95px 60px 70px)",
				   "margin-top":"-25px"});
				}
			);
			
			
			
			$(".img3").mouseover(
				function(){
				   $(".img3").css({"clip":"rect(65px 160px 100px 124px)",
				   "margin-top":"-60px"});
				}
			);
			$(".img3").mouseout(
				function(){
				   $(".img3").css({"clip":"rect(30px 160px 60px 124px)",
				   "margin-top":"-25px"});
				}
			);
			
			
			$(".img4").mouseover(
				function(){
				   $(".img4").css({"clip":"rect(70px 205px 100px 184px)",
				   "margin-top":"-60px"});
				}
			);
			$(".img4").mouseout(
				function(){
				   $(".img4").css({"clip":"rect(30px 205px 60px 184px)",
				   "margin-top":"-25px"});
				}
			);	
		}
	);
	function linkto(id){
		if(id==1){
		//alert($(".img1").width());
		location.href="http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=http://www.baicai58.com&title=百财网，最好用的P2P网贷网站&pics=&summary=";
		}
		if(id==2){
		location.href="http://widget.renren.com/dialog/share?resourceUrl=http://www.baicai58.com&srcUrl=http://www.baicai58.com&title='百财网，最好用的P2P网贷网站'&pic=&description=";
		}
		if(id==3){
		location.href="http://v.t.sina.com.cn/share/share.php?url=http://www.baicai58.com&title='百财网,最好用的P2P网贷网站'";
		}
		if(id==4){
		location.href="http://v.t.qq.com/share/share.php?url=http://www.baicai58.com&title='百财网,最好用的P2P网贷网站'";
		}	
	}
</script>
<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-xs-4 col-xs-offset-1">
                <div class="footer_p">
                <i class="fa  fa-phone-square fa-4x"></i><br><br>
                <span>地址: 苏州工业园区苏雅路318号天翔国际大厦1505室</span><br>
                <span>电话/传真: 0512-62853600</span><br>
                <span>邮箱: service@baicai58.com</span><br>
                <span>邮编: 215123</span><br>
            </div>
            </div>
            <div class="col-xs-4">
                <div class="footer_p">
                <i class="fa  fa-info-circle fa-4x"></i><br><br>
                <span>百财网络科技(苏州)有限公司技术支持</span><br>
                <span>苏ICP备15013328号-1</span>
            </div>
            </div>
            <div class="col-xs-3">
                 <div class="footer_p">
               <i class="fa  fa-weixin fa-4x"></i><br><br>
                <img width="85" height="85" src="../../../../Style/A/images/erweima.png"/>
                </div>
            </div>

        </div>
	    <div class="row text-center" style="border-top:1px dashed #fff;margin-top:10px;padding:10px 0;">

            <a id='___szfw_logo___' href='https://search.szfw.org/cert/l/CX20150626010873010617' target='_blank'><img src='/Style/H/images/cert.jpg'/></a>
            <a href="http://www.baicai58.com/" target="_blank"><img src="/Style/H/images/f4.png" /></a>
            <a href="http://www.baicai58.com/" target="_blank"><img src="/Style/H/images/f5.png" /></a>
            <a href="http://webscan.360.cn/index/checkwebsite/url/www.baicai58.com" target="_blank"><img src="/Style/H/images/f2.png" /></a>
        </div>
    </div>
</div>
</body></html>