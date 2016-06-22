<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0,minimal-ui"> 
	<meta name="full-screen" content="yes">
	<title><?php echo ($glo["index_title"]); ?></title>
	<link type="text/css" rel="stylesheet" href="/Style/Font-Awesome/css/font-awesome.min.css">
	<link type="text/css" rel="stylesheet" href="/Style/JBox/Skins/Currently/jbox.css">
	<link type="text/css" rel="stylesheet" href="/Style/bootstrap-3.3.5/css/bootstrap.min.css">
	<link type="text/css" rel="stylesheet" href="/Style/Mobile/css/common.css">
	<link type="text/css" rel="stylesheet" href="/Style/Mobile/css/index.css">
	<script type="text/javascript" src="/Style/Js/jquery-1.12.1.min.js"></script>
	<script type="text/javascript" src="/Style/bootstrap-3.3.5/js/bootstrap.min.js"></script>
	<script  src="/Style/JBox/jquery.jBox.min.js" type="text/javascript"></script>
	<script src="/Style/JBox/jquery.jBoxConfig.js" type="text/javascript"></script>
	<script type="text/javascript" src="__ROOT__/Style/Mobile/jquery-global.js"></script> 
	<script type="text/javascript" src="/Style/Js/jTools.js"></script>
</head>
<body>
	<header class="container">
			<div class="row">
				<a href="javascript:history.go(-1)" >
					<div class="col-xs-2">
		    			<i  class="fa fa-angle-left text-gray" ></i>
		    		</div>
			   	</a>
			   	<div class="col-xs-8 header-title text-center" id="header-title"></div>
			   	<div class="col-xs-2  text-right">
			   		<div class="dropdown">
						<a data-toggle="dropdown" href="#"><i  class="fa fa-navicon text-gray" style="text-align: right;"></i></a>
						<ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dLabel">
							<li>
								<a  href="__APP__/M/user/fund">我的账户</a>
							</li>
							<li class="divider"></li>
							<li>
								<a  href="__APP__/M/user/safe" >安全中心</a>
							</li> 
							<li class="divider"></li>
							<li>
								<a  href="__APP__/M/user/msg" >站内信</a>
							</li>    
							<li class="divider"></li>
							<li>
							<?php if($_SESSION['u_id']): ?><a href="__APP__/M/Pub/logout" style="color:#DE002A">退出账号</a>
							
							<?php else: ?> 
							
								<a href="__ROOT__/M/Pub/login" style="color:#0099FF">请先登录</a><?php endif; ?>
							</li>
						</ul>
					</div>
			   	</div>
			</div>
    </header> 
<script>
	$(function(){
		$('header').hide();
		$('body').css('padding-top',0);
	});
</script>
    <div class="container text-center intro bg-primary">
    	<div class="row text-center">
            <h3>百财网</h3>
        </div>

        <div class="row">
            <small>年化收益</small><strong class="text-red">10.8</strong><small>%</small>
            <small>投资期限</small><strong class="text-red">8-50</strong><small>天</small>
        </div>

        <div class="col-xs-4">
            <div>
                <span class="glyphicon glyphicon-lock text-yellow" ></span>
                <h6>资金安全</h6>
            </div>
        </div>
        <div class="col-xs-4">
            <div>
                <span class="glyphicon glyphicon-piggy-bank text-yellow"></span>
                <h6>收益丰厚</h6>
            </div>
        </div>
        <div class="col-xs-4">
            <div>
                <span class="glyphicon glyphicon-plane text-yellow"></span>
                <h6>短期回款 </h6>
            </div>
        </div>
    </div>

	<div class="container safe">
            <div class="panel panel-warning">
                <div class="panel-heading"> <h5 class="panel-title">安全保障</h5></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-2"><i class="fa fa-database text-green"></i></div>
                        <div class="col-xs-10">足额抵押 <small>保障还款来源</small></div>

                    </div>
                    <div class="row">
                        <div class="col-xs-2"><i class="fa fa-users text-yellow"></i></div>
                        <div class="col-xs-10">团队专业 <small>规避业务风险</small></div>
                    </div>
                    <div class="row">

                        <div class="col-xs-2"><i class="fa fa-cc-visa text-primary"></i></div>
                        <div class="col-xs-10">第三方支付平台 <small>资金安全透明</small></div>
                    </div>
                </div>
            </div>
        </div>
	 <div class="container home-btns">
	
		<?php if($uname): ?><a href="<?php echo U('M/User/index');?>" class="btn btn-primary btn-lg center-block center-block">
				<i class="fa fa-user fa-lg"></i>我的百财
			</a>
		
		
		<?php else: ?>
		
			<a href="<?php echo U('M/Pub/login');?>" class="btn btn-info btn-lg center-block">
				<i class="fa fa-user fa-lg"></i>用户登录
			</a>
		
			<a href="<?php echo U('M/Pub/regist');?>" class="btn btn-danger btn-lg center-block">
				<i class="fa fa-pencil fa-lg"></i>免费注册
			</a><?php endif; ?>
		
			<a href="<?php echo U('M/Help/product_intro');?>" class="btn btn-warning btn-lg center-block">
				<i class="fa fa-book fa-lg"></i>产品解读
			</a>
		
		
	</div> 
	


﻿<footer>
	<nav>
		<ul class="fmenu">
			<li>
				<a href="<?php echo U('M/Index/index');?>" >
					<i class="fa fa-home"></i>
					<span>首页</span>
				</a>
			</li>
			<li>
				<a href="<?php echo U('M/Loaninvest/index');?>">
					<i class="fa fa-rmb"></i>
					<span>理财</span>
				</a>
			</li>
			<li>
				<a href="<?php echo U('M/Help/index');?>">
					<i class="fa fa-question"></i>
					<span>帮助</span>
				</a>
			</li>
			<li>
				<a href="<?php echo U('M/User/index');?>">
					<i class="fa fa-user"></i>
					<span>我</span>
				</a>
			</li>		
		</ul>
	</nav>
	
</footer>

<script type="text/javascript">  
	$(function () {
			var index=readCookie('index');
			index=index?index:0;
			$('.fmenu>li:eq('+index+')').css({
				'color':'#F08012'
			}).find('a').eq(0).css({'color':'#F08012'});
            $('.fmenu li').each(function () {
                $(this).click(function(){
                    writeCookie('index',$(this).index());
				   
				})
            });

			$("#header-title").text(localStorage.title);

     })

var browser = navigator.userAgent;
function orientationChange() {
    if(browser.indexOf('Android') > -1 || browser.indexOf('Linux') > -1){
        manWidth(screen.width);
    }
    
}

addEventListener('load', function(){ 
    orientationChange();
    window.onorientationchange = orientationChange;
}); 


function writeCookie(name, value) {  
    exp = new Date();  
    exp.setTime(exp.getTime() + (86400 * 1000 * 30)); 
    document.cookie = name + "=" + escape(value) + "; expires=" + exp.toGMTString() + "; path=/";  
}  
function readCookie(name) {  
    var search;  
    search = name + "=";  
    offset = document.cookie.indexOf(search);  
    if (offset != -1) {  
        offset += search.length;  
        end = document.cookie.indexOf(";", offset);  
        if (end == -1){ 
            end = document.cookie.length; 
        } 
        return unescape(document.cookie.substring(offset, end));  
    }else{ 
        return ""; 
    } 
} 

</script> 
</body>
</html>