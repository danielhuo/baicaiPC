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

<div class="page_tit">批量添加地区</div>
<div class="page_tab"></div>
<div class="form2">
	<form method="post" action="__URL__/doAddMul" onsubmit="return subcheck();">
	<div id="tab_1">
	
	<dl class="lineD"><dt>地区名称：</dt><dd><textarea name="area_name" id="area_name"  class="areabox" ></textarea><span id="tip_area_name" class="tip">用,号隔开</span></dd></dl>
	<dl class="lineD"><dt>上级地区：</dt><dd><select name="reid" id="reid"   class="c_select"><option value="">一级地区</option><?php foreach($area_list as $key=>$v){ if("" && $v[""]==$_X[""]){ ?><option value="<?php echo ($v["id"]); ?>" selected="selected"><?php echo ($v["name"]); ?></option><?php }else{ ?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v["name"]); ?></option><?php }} ?></select><span id="tip_reid" class="tip">一级地区则无父分类</span></dd></dl>
	
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