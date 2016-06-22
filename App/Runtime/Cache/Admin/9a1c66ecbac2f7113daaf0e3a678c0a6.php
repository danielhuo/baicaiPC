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
<div class="container">
	<div class="panel panel-primary">
	    <div class="panel-heading clearfix">
	        <span class="panel-title">房源</span>
	    </div>
	<table class="table">
	    <thead>
	        <tr><th>id</th><th>户主<th>售价</th><th>面积</th><th>户型</th><th>区域</th><th>状态</th><th>操作</th></tr>
	    </thead>
	    <tbody>
	    	<?php if(is_array($houses)): $i = 0; $__LIST__ = $houses;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
	    		<td><?php echo ($vo["id"]); ?></td>
	    		<td><a onclick="loadUser(<?php echo ($vo["uid"]); ?>,'<?php echo ($vo["user_name"]); ?>')" class="text-info" href="javascript:void(0);"><?php echo ($vo["user_name"]); ?></a></td>
	    		<td><?php echo ($vo["price"]); ?>万</td>
	    		<td><?php echo ($vo["size"]); ?></td>
	    		<td><?php echo ($vo["bed_room"]); ?>室<?php echo ($vo["live_room"]); ?>厅<?php echo ($vo["toilet"]); ?>卫</td>
	    		<td><?php echo ($vo["xian"]); ?>-<?php echo ($vo["zhen"]); ?>-<?php echo ($vo["village"]); ?></td>

	    		<td>
	    			<?php switch($vo["status"]): case "0": ?>待审核<?php break;?>
	    				<?php case "1": ?>挂牌中<?php break;?>
	    				<?php case "1": ?>未通过<?php break;?>
	    				<?php case "3": ?>已关闭<?php break;?>
	    				<?php default: endswitch;?>
	    			
	    		</td>
	    		<td><a href="__URL__/edit?id=<?php echo ($vo["id"]); ?>" class="text-warning">编辑</a></td>
	    	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
	    </tbody>
	</table>
	<div class="panel-footer text-right"><?php echo ($pagebar); ?></div>
	</div>
</div>
<script type="text/javascript" src="__ROOT__/Style/A/js/adminbase.js"></script>
</body>
</html>