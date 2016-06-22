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
	<style>
		.bList{margin-top:10px;}
		.bList .panel>a{color:#666;}
	    .bList [class*='col-xs']{padding-left:5px;padding-right: 0;height:20px;line-height:20px;}
	    .btn-link{color:#fff;}
	</style>
	
		<div class="bList">
			<?php if(is_array($list["list"])): $i = 0; $__LIST__ = $list["list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="panel panel-info">
				<a href="__URL__/detail?id=<?php echo ($vo["id"]); ?>" class="panel-body center-block">
					<div class="col-xs-3">
						<strong class="text-danger "><?php echo ($vo["interest_rate"]); ?></strong>
						<small>%</small>
					</div>
					<div class="col-xs-3 text-danger"><?php echo ($vo["loan_amount"]); ?></div>
					<div class="col-xs-2"><small>约</small><strong class="text-danger "><?php echo ($vo["loan_duration"]); ?></strong><small>天</small></div>
					<div class="col-xs-4">
						<div class="progress progress-striped active">
						   <div class="progress-bar progress-bar-success" role="progressbar" 
						      aria-valuenow="<?php echo (floor($vo["progress"])); ?>" aria-valuemin="0" aria-valuemax="100" 
						      style="width:<?php echo (floor($vo["progress"])); ?>%;">
						      <span ><?php echo (floor($vo["progress"])); ?>%</span>
						   </div>
						</div>
					</div>
				</a>
			</div><?php endforeach; endif; else: echo "" ;endif; ?>
			</div>
		
		<div class="fixed_div text-center" style="margin-top:10px;height: 20px;">
			<span class="loading" style="display: none;"><i class="fa fa-spinner fa-pulse"></i>加载中</span>
		    <span class="nomore" style="display: none;">没有更多记录了</span>
		</div>
	
<script type="text/javascript">

	localStorage.title="投资列表";
	
    var nowPage = <?php echo ($list["page"]["nowPage"]); ?>;
    var total = <?php echo ($list["page"]["total"]); ?>;
	var a=0;
    function getInfo() {
    	
   		var scrollTop = $(this).scrollTop();
        var docHeight = $(document).height();
        var windowHeight =$(window).height();
      	var remain=(docHeight-scrollTop-windowHeight)/windowHeight;
        if (remain==0) {
        	
			nextPage = nowPage+1;
			$(".fixed_div .loading").show();
			
	        $.ajax({
	            type: 'GET',
	            url:"",
	            dataType:"json",
	            data:{"p":nextPage},
	           
	            success: function (res) {
					for(var i in res){
						var html="<div class='panel panel-info'>";
							html+="<a href='__URL__/detail?id="+res[i].id+"' class='panel-body center-block'><div class='col-xs-3'><strong class='text-danger'>"+res[i].interest_rate+"</strong>";
							html+="<small>%</small></div>";
							html+="<div class='col-xs-3 text-danger'>"+res[i].loan_amount+"</div>";
							html+="<div class='col-xs-2'><small>约</small><strong class='text-danger'>"+res[i].loan_duration+"</strong><small>天</small></div>";
							html+="<div class='col-xs-4'><div class='progress progress-striped active'>";
							html+="<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow="+Math.floor(res[i].progress)+"aria-valuemin='0' aria-valuemax='100' style='width:"+Math.floor(res[i].progress)+"%;'>";
							html+=" <span >"+Math.floor(res[i].progress)+"%</span></div></div></div></a></div>";
							
							$(".bList").append(html);
							$(".fixed_div .loading").hide();
						}
					nowPage++;
	            },
				
	        });
		}
    }
	
	$(window).scroll(function(){
		if(nowPage<total){
		    getInfo();
	    }else{
			$(".fixed_div .loading").hide();
			$(".fixed_div .nomore").show();
		}
	
	})
</script>
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