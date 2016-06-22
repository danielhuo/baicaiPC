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
	var delUrl = '__URL__/doDelete';
</script>
<div class="so_main">
  <div class="page_tit"><?php echo ($position); ?>管理</div>
  <div class="Toolbar_inbox">
  	<div class="page right"><?php echo ($pagebar); ?></div>
    <a class="btn_a" href="__URL__/add"><span>添加用户组</span></a>
    <a onclick="del();" class="btn_a" href="javascript:void(0);"><span>删除用户组</span></a>
  </div>
  
  <div class="list">
  <table id="area_list" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th style="width:30px;">
        <input type="checkbox" id="checkbox_handle" onclick="checkAll(this)" value="0">
        <label for="checkbox"></label>
    </th>
    <th class="line_l">ID</th>
    <th class="line_l">用户组名称</th>
    <th class="line_l">操作</th>
  </tr>
  	<?php $_REQUEST['p'] = isset($_REQUEST['p'])?$_REQUEST['p']:0; $cpage = (intval($_REQUEST['p'])<=1)?0:intval($_REQUEST['p']); $j=($cpage*C('ADMIN_PAGE_SIZE') + 1); ?>
  <?php if(is_array($acl_list)): $i = 0; $__LIST__ = $acl_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr overstyle='on' id="list_<?php echo ($vo["id"]); ?>">
        <td><input type="checkbox" name="checkbox" id="checkbox2" onclick="checkon(this)" value="<?php echo ($vo["id"]); ?>"></td>
        <td><?php echo $j; ?></td>
        <td><span id="name_<?php echo ($vo['id']); ?>"><?php echo ($vo["groupname"]); ?></span></td>
        <td>
            <a href="__URL__/edit?a_id=<?php echo ($vo["id"]); ?>">编辑</a> 
            <a href="javascript:void(0);" onclick="del(<?php echo ($vo['id']); ?>);">删除</a>  
        </td>
      </tr>
	<?php $j++; endforeach; endif; else: echo "" ;endif; ?>
  </table>

  </div>
  <div class="Toolbar_inbox">
  	<div class="page right"><?php echo ($pagebar); ?></div>
    <a class="btn_a" href="__URL__/add"><span>添加用户组</span></a>
    <a onclick="del();" class="btn_a" href="javascript:void(0);"><span>删除用户组</span></a>
  </div>
</div>

<script type="text/javascript" src="__ROOT__/Style/A/js/adminbase.js"></script>
</body>
</html>