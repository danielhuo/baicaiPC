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

<div class="so_main">

<div class="page_tit">添加地区</div>
<div class="form2">
	<form method="post" action="__URL__/doAdd" onsubmit="return subcheck();">
	<div id="tab_1">
	
	<dl class="lineD"><dt>地区名称：</dt><dd><input name="name" id="name"  class="input" type="text" value="" ><span id="tip_name" class="tip">*</span></dd></dl>
	<dl class="lineD"><dt>地区排序：</dt><dd><input name="sort_order" id="sort_order"  class="input" type="text" value="" ><span id="tip_sort_order" class="tip">数字越大越靠前</span></dd></dl>
	<dl class="lineD"><dt>上级地区：</dt><dd><select name="reid" id="reid" class="c_select">
		<?php if(is_array($area_list)): $i = 0; $__LIST__ = $area_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if(($vo["id"]) == $re["id"]): ?>selected<?php endif; ?>><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?> 
	</select></dd></dl>
	
	</div><!--tab1-->
	
	<div class="page_btm">
	  <input type="submit" class="btn_b" value="确定" />
	</div>
	</form>
</div>

</div>
<script type="text/javascript" src="__ROOT__/Style/A/js/adminbase.js"></script>
</body>
</html>