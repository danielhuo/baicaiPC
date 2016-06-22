<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
<title><?php echo ($ts['site']['site_name']); ?>管理后台</title>

<link href="__ROOT__/Style/A/css/style.css" rel="stylesheet" type="text/css">
<link href="__ROOT__/Style/A/css/admin.css" rel="stylesheet" type="text/css">
<link href="__ROOT__/Style/A/js/tbox/box.css" rel="stylesheet" type="text/css" />
<link href="__ROOT__/Style/bootstrap-3.3.5/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="__ROOT__/Style/JBox/Skins/Blue/jbox.css"/><!-- `mxl:teamreward` --><!-- 2014.10.13增补 -->
<link type="text/css" rel="stylesheet" href="/Style/bootstrap-3.3.5/css/bootstrap.min.css"/>
<script type="text/javascript" src="__ROOT__/Style/A/js/jquery.js"></script>
<script type="text/javascript" src="__ROOT__/Style/A/js/common.js"></script>
<script type="text/javascript" src="__ROOT__/Style/A/js/tbox/box.js"></script>
<script type="text/javascript" src="/Style/My97DatePicker/WdatePicker.js" language="javascript"></script>
<script  src="__ROOT__/Style/JBox/jquery.jBox.min.js" type="text/javascript"></script><!-- `mxl:teamreward` -->
<script  src="__ROOT__/Style/JBox/jquery.jBoxConfig.js" type="text/javascript"></script><!-- `mxl:teamreward` -->
</head>
<body>
<style type="text/css">
.quxiantu{ margin-top:30px;}
.qleft{ float:left; width:50%; text-align:left;}
.qright{ float:right; width:50%; text-align:right;}

.ssx a{height:30px; line-height:30px}
.lf{
    float:left;
    width:48%; border:1px solid #c7d8ea; margin: 10px;
}
.lf h6{
    border-bottom: 1px solid #c7d8ea;
    color: #3a6ea5;
    height: 26px;
    line-height: 28px;
    padding: 0 10px;
    font-size: 13px;
}
.lf .content{
    padding: 9px 10px;
    line-height: 22px;
}
.lf .content a{
    color:red;
    
}
</style>
<script type="text/javascript" src="__ROOT__/Style/Js/highcharts.js"></script>
<script type="text/javascript" src="__ROOT__/Style/Js/exporting.js"></script>
<div class="so_main">
  <div class="page_tit">欢迎页</div>
  <!--列表模块-->
  <div class="Toolbar_inbox">
    <div class="page right">
	当前时间<span id="clock"></span>
    </div>
    <a href="javascript:;" class="btn_a"><span>欢迎登陆</span></a></div>
<script>
function changeClock()
{
	var d = new Date();
	document.getElementById("clock").innerHTML = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate() + " " + d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
}
window.setInterval(changeClock, 1000);
</script>  
<div class="lf">
    <h6>个人信息</h6>
    <div class="content">
        您好，<?php echo ($user["user_name"]); ?>
        <br />
        所属角色：<?php echo ($user["groupname"]); ?> 
        <br />
        上次登录时间：<?php echo (date('Y-m-d H:i:s',$user["last_log_time"])); ?>
        <br />
        上次登录IP：<?php echo ($user["last_log_ip"]); ?>   
    </div>
</div>
<div class="lf">
    <h6>系统信息</h6>
	 <div class="content">
     <div style="float: left; width:300px;">
        百财网版本：超级后台版
     </div>
    <div style="float: left;">
        操作系统：<?php echo ($service["service_name"]); ?> 
     </div>
     <br />
	<div style="float: left; width:300px;">
       服务器软件：<?php echo ($service["service"]); ?>
     </div>
    <div style="float: left;">
        MySQL 版本：<?php echo ($service["mysql"]); ?>
     </div>
     <br />
	 <div style="float: left; width:300px;">
      服务器协议：<?php echo ($_SERVER['SERVER_PROTOCOL']); ?>
     </div>
    <div style="float: left;">
      服务器名称：<?php echo ($_SERVER['SERVER_NAME']); ?>
     </div>
     <br />
	 <div style="float: left; width:300px;">
      PHP运行方式：<?php echo strtoupper(php_sapi_name())?>
     </div>
    <div style="float: left;">
      PHP版本：<?php echo PHP_VERSION?>
     </div>
	<br />
	 </div>
</div>
<div class="lf">
    <h6>待审核工作</h6>
	
    <div class="content">
		<!--
		<div style="float: left; width:300px;">
        等待初审的标[<?php if($row["borrow_1"] > 0): ?><a href="__APP__/admin/borrow/waitverify.html" ><?php echo ($row["borrow_1"]); ?></a><?php else: ?> 0<?php endif; ?>]个
		</div>
		<div style="float: left; width:300px;">
        等待复审的标[<?php if($row["borrow_2"] > 0): ?><a href="__APP__/admin/borrow/waitverify2.html"><?php echo ($row["borrow_2"]); ?></a><?php else: ?> 0<?php endif; ?>]个
		</div>
		<div style="float: left; width:300px;">
        等待划款的标[<?php if($row["transferring"] > 0): ?><a href="__APP__/admin/borrow/transferring.html"><?php echo ($row["transferring"]); ?></a><?php else: ?> 0<?php endif; ?>]个
		</div>
		<div style="float: left; width:300px;">
        等待追保审核的标[<?php if($row["depositadding"] > 0): ?><a href="__APP__/admin/borrow/depositadding.html"><?php echo ($row["depositadding"]); ?></a><?php else: ?> 0<?php endif; ?>]个
     </div>
     <br />
	 <div style="float: left; width:300px;">
        等待转让审核的标[<?php if($row["debting"] > 0): ?><a href="__APP__/admin/debt/index.html"><?php echo ($row["debting"]); ?></a><?php else: ?> 0<?php endif; ?>]个
     </div>
     <br />
	-->
     <div style="float: left; width:300px;">
        等待初审的票据[<?php if($row["bill_1"] > 0): ?><a href="__APP__/admin/bill/waitverify.html" ><?php echo ($row["bill_1"]); ?></a><?php else: ?> 0<?php endif; ?>]个
     </div>
     <div style="float: left; width:300px;">
        等待复审的票据[<?php if($row["bill_2"] > 0): ?><a href="__APP__/admin/bill/waitverify2.html"><?php echo ($row["bill_2"]); ?></a><?php else: ?> 0<?php endif; ?>]个
     </div>
	 <div style="float: left; width:300px;">
        等待划款的票据[<?php if($row["bill_3"] > 0): ?><a href="__APP__/admin/bill/transferring.html"><?php echo ($row["bill_3"]); ?></a><?php else: ?> 0<?php endif; ?>]个
     </div>
	 <div style="float: left; width:300px;">
        一周到期的票据[<?php if($row["oneweekleft"] > 0): ?><a href="__APP__/admin/bill/doweek?isShow=1"><?php echo ($row["oneweekleft"]); ?></a><?php else: ?> 0<?php endif; ?>]个
     </div>
	 
	 
	    <div style="float: left; width:300px;">
        等待初审的借款[<?php if($row["loan_1"] > 0): ?><a href="__APP__/admin/loan/waitverify.html" ><?php echo ($row["loan_1"]); ?></a><?php else: ?> 0<?php endif; ?>]个
     </div>
     <div style="float: left; width:300px;">
        等待复审的借款[<?php if($row["loan_2"] > 0): ?><a href="__APP__/admin/loan/waitverify2.html"><?php echo ($row["loan_2"]); ?></a><?php else: ?> 0<?php endif; ?>]个
     </div>
	 <div style="float: left; width:300px;">
        等待划款的借款[<?php if($row["loan_3"] > 0): ?><a href="__APP__/admin/loan/transferring.html"><?php echo ($row["loan_3"]); ?></a><?php else: ?> 0<?php endif; ?>]个
     </div>
	 <div style="float: left; width:300px;">
        一周到期的借款[<?php if($row["oneweekleft1"] > 0): ?><a href="__APP__/admin/loan/doweek?isShow=1"><?php echo ($row["oneweekleft1"]); ?></a><?php else: ?> 0<?php endif; ?>]个
     </div>
	 <div style="float: left; width:300px;">
        待流标操作的借款[<?php if($row["loan_4"] > 0): ?><a href="__APP__/admin/loan/waitmoney.html?"><?php echo ($row["loan_4"]); ?></a><?php else: ?> 0<?php endif; ?>]个
     </div>
	 
	 
     <br />
	 <div style="float: left; width:300px;">
        待处理的借款预约[<?php if($row["accesstel1"] > 0): ?><a href="__APP__/admin/reservation/accesstel?type=1"><?php echo ($row["accesstel1"]); ?></a><?php else: ?> 0<?php endif; ?>]个
     </div>
     <br />
	  <div style="float: left; width:300px;">
        待处理的投资预约[<?php if($row["accesstel2"] > 0): ?><a href="__APP__/admin/reservation/accesstel?type=2"><?php echo ($row["accesstel2"]); ?></a><?php else: ?> 0<?php endif; ?>]个
     </div>
     <br />
	 
	 <!--
	 <div style="float: left; width:300px;">  
        等待VIP认证的[<?php if($row["vip_a"] > 0): ?><a href="__APP__/admin/vipapply/index?status=0"><?php echo ($row["vip_a"]); ?></a><?php else: ?> 0<?php endif; ?>]个
     </div>
	  <br />
	  
     <div style="float: left; width:300px;">
        额度申请等待审核的[<?php if($row["limit_a"] > 0): ?><a href="__APP__/admin/members/infowait.html"><?php echo ($row["limit_a"]); ?></a><?php else: ?> 0<?php endif; ?>]个 
     </div>
	 <br />
     <div style="float: left; width:300px;"> 
        上传资料等待审核的[<?php if($row["data_up"] > 0): ?><a href="__APP__/admin/memberdata/index.html"><?php echo ($row["data_up"]); ?></a><?php else: ?> 0<?php endif; ?>]个
     </div>
     <br />
	 -->
     <div style="float: left; width:300px;">   
        等待审核提现[<?php if($row["withdraw"] > 0): ?><a href="__APP__/admin/Withdrawlogwait/index.html"><?php echo ($row["withdraw"]); ?></a><?php else: ?> 0<?php endif; ?>]个
     </div>
	  <br />
	  <br />
    </div>
</div>
<div class="lf">
    <h6>开发团队</h6>
    <div class="content">
        版权所有：百财网络科技有限公司
        <br />
        总 策 划：
        <br />
        研发团队：
        <br />    
        设计团队：
		<br />    
        售后团队：
        <br />    
        官方网站：<a href="http://www.baicai58.com" target="_blank">http://www.baicai58.com/</a> 
        <br />    
        网贷点评网：<a href="http://www.wangdaidp.com/" target="_blank">http://www.wangdaidp.com/</a>
    </div>
</div>
</div>
<script type="text/javascript" src="__ROOT__/Style/A/js/adminbase.js"></script>
</body>
</html>