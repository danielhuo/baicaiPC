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

<script type="text/javascript">
	var delUrl = '__URL__/doDel';
	var addUrl = '__URL__/add';
	var isSearchHidden = 1;
</script>
<div class="so_main">
  <div class="page_tit">待初审借款</div>
<!--搜索/筛选会员-->
  
<!--搜索/筛选会员-->

  <div class="Toolbar_inbox">
  	<div class="page right"><?php echo ($pagebar); ?></div>
    <a onclick="dosearch();" class="btn_a" href="javascript:void(0);"><span class="search_action">搜索/筛选借款</span></a>
  </div>
  
  <div class="list">
  <table id="area_list" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th style="width:30px;">
        <input type="checkbox" id="checkbox_handle" onclick="checkAll(this)" value="0">
        <label for="checkbox"></label>
    </th>
	<th class="line_l">标题</th>
    <th class="line_l">融资人</th>
    <th class="line_l">融资额</th>
	<th class="line_l">年利率</th>
	<th class="line_l">年费率</th>
    <th class="line_l">借款期限</th>
    <th class="line_l">操作</th>
  </tr>
  <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr overstyle='on' id="list_<?php echo ($vo["id"]); ?>">
        <td><input type="checkbox" name="checkbox" id="checkbox2" onclick="checkon(this)" value="<?php echo ($vo["id"]); ?>"></td>
		<td>JK<?php echo ($vo["id"]); ?>&nbsp;<?php echo (cnsubstr($vo["loan_name"],12)); ?></td>
        <td><a onclick="loadUser(<?php echo ($vo["mid"]); ?>,'<?php echo ($vo["user_name"]); ?>')" href="javascript:void(0);"><?php echo ($vo["user_name"]); ?></a></td>
        
        <td><?php echo ($vo["loan_amount"]); ?></td>
		<td><?php echo ($vo["interest_rate"]); ?>%</td>
		<td><?php echo ($vo["fee_rate"]); ?>%</td>
        <td><?php echo ($vo["loan_duration"]); if($vo["duration_type"] == 1): ?>个月<?php else: ?>天<?php endif; ?></td>
        <td>
            <a href="__URL__/edit?id=<?php echo ($vo['id']); ?>">审核</a> 
        </td>
      </tr><?php endforeach; endif; else: echo "" ;endif; ?>
  </table>

  </div>
  
  <div class="Toolbar_inbox">
  	<div class="page right"><?php echo ($pagebar); ?></div>
    <a onclick="dosearch();" class="btn_a" href="javascript:void(0);"><span class="search_action">搜索/筛选借款</span></a>
  </div>
</div>


<script type="text/javascript" src="__ROOT__/Style/A/js/adminbase.js"></script>
</body>
</html>