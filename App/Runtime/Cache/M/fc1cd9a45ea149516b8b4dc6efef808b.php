<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
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
	form{padding: 5px 0;}
	form select{
		width:30%;height: 40px;margin-left:2%;
		border-radius:10%;border:none;font-size: 16px;
		background-color: #fff;
		text-indent: 2px;
	}
	.row{display: block;height: 40px;line-height: 40px;
		background-color: #fff;padding-right:10px;
	}
	.row:first-child{font-size:16px;font-weight:bold;color: #000;}
	.row:not(:first-child){border:1px solid #666;margin-top:10px;}
	.row span{display:block;float:left;}
	.row span:first-child{width: 15%;text-align: center;}
	.row span:nth-child(2){width:20%;text-align: center;}
	.row span:nth-child(3),.row span:nth-child(4){width:20%;text-align:right;}
	.row span:nth-child(5){width:25%;text-align:right;color:#E5E5E5;font-size:28px;}
	.row:nth-child(2) span:first-child{font-size: 20px;color:#FF0000;font-style:italic;}
	.row:nth-child(3) span:first-child{font-size: 18px;color:#ff0000;font-style:italic;}
	.row:nth-child(4) span:first-child{font-size: 16px;color:#ff0000;font-style:italic;}
	.switch{
		overflow: hidden;
		height: 50px;line-height: 50px;font-size: 20px;
		font-weight: bold;
	}
	.left,.right{width:50%;text-align: center;}
	.nowitem{background-color:#fff;border-top: 2px solid #ff6600;color:#ff6600;}
	.content{padding-top: 10px;background-color: #fff;}
</style>
<script >
	$(function(){
		$('#period').val(<?php echo ($period); ?>);
		$('#type').val(<?php echo ($type); ?>);
		$('form').change(function(){
			$(this).submit();
		})

	})
	
</script>
<div class="body">
	<form action="" method="post">
		<select name="period" id="period" style="width:100px;">
			<option value="1">所有月</option>
			<option value="2">当前月</option>
			<option value="3">上一月</option>
		</select>
		<select name="type" id="type">
			<option value="0">金额榜</option>
			<option value="1">人数榜</option>
		</select>
	</form>
	<div>
		<?php if($type == 1): ?><div class="content">
			<a href="javascript:void(0);" class="row">
				<span>排名</span>
				<span>姓名</span>
				<span>推广数</span>
				<span>推广额</span>
			</a>
			<?php if(is_array($Des)): $i = 0; $__LIST__ = $Des;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="javascript:void(0);" class="row">
				<span><?php echo ($key+1); ?></span>
				<span><?php echo ($vo["real_name"]); ?></span>
				<span><?php echo ($vo["recommend_number"]); ?></span>
				<span><?php echo (numformat($vo["recommend_money"],0)); ?></span>
				<span>></span>
				</a><?php endforeach; endif; else: echo "" ;endif; ?>
		</div><?php else: ?>

		<div class="content">
			<a href="javascript:void(0);" class="row">
				<span>排名</span>
				<span>姓名</span>
				<span>推广额</span>
				<span>推广数</span>		
			</a>
			<?php if(is_array($Des)): $i = 0; $__LIST__ = $Des;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="javascript:void(0);" class="row">
				<span><?php echo ($key+1); ?></span>
				<span><?php echo ($vo["real_name"]); ?></span>
				<span><?php echo (numformat($vo["recommend_money"],0)); ?></span>
				<span><?php echo ($vo["recommend_number"]); ?></span>
				<span>></span>
				</a><?php endforeach; endif; else: echo "" ;endif; ?>
		</div><?php endif; ?>
	</div>
</div>
<script language="javascript">
 localStorage.title="百财琅琊榜";
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