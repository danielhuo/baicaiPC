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
	$(function(){
	  alert(size({vo}));
	})
</script>
<div class="container">
    <div class="panel panel-info">
    
    <div class="panel-heading">
        <h4 class="panel-title">还款中的借款</h4>
    </div>
    <div class="panel-body">
    <table id="area_list" class="table">
        <thead>
            <tr>
                <th>
                    <input type="checkbox" id="checkbox_handle" onclick="checkAll(this)" value="0">
                    <label for="checkbox"></label>
                </th>
                <th >借款项目</th>
                <th >借款人</th>
                <th >发标额</th>
                <th >实募额</th>
                <th >预期期限</th>
                <th >起息日</th>
                <th >预期还款日</th>
                <th >操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr overstyle='on' id="list_<?php echo ($vo["id"]); ?>">
                <td>
                    <input type="checkbox" name="checkbox" id="checkbox2" onclick="checkon(this)" value="<?php echo ($vo["id"]); ?>">
                </td>
                <td>
                    <a href="__APP__/Loaninvest/detail?id=<?php echo ($vo["id"]); ?>" title="<?php echo ($vo["name"]); ?>" target="_blank">JK<?php echo ($vo["id"]); ?>&nbsp;<?php echo (cnsubstr($vo["loan_name"],12)); ?></a>
                </td>
                <td><a onclick="loadUser(<?php echo ($vo["mid"]); ?>,'<?php echo ($vo["user_name"]); ?>')" href="javascript:void(0);"><?php echo ($vo["user_name"]); ?></a></td>
                <td><?php echo (num_format($vo["loan_amount"])); ?></td>
                <td><?php echo (num_format($vo["has_collect"])); ?></td>
                <td><?php echo ($vo["loan_duration"]); echo (duration_format($vo["duration_type"])); ?></td>
                <td><?php echo (date("Y-m-d",$vo["begin_time"])); ?></td>
                <td><?php echo (date("Y-m-d",$vo["deadline"])); ?></td>
                <td>       
                    <a href="/admin/loan/doinvest?id=<?php echo ($vo["id"]); ?>">投资人记录</a>&nbsp;
                    <a href="/admin/loan/schedule?id=<?php echo ($vo["id"]); ?>">进度跟踪</a>
                    <a href="/admin/loan/qingsuan?id=<?php echo ($vo["id"]); ?>">清算</a>&nbsp;
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
    </div>
    <div class="panel-footer">
        <a href="__URL__/doweek?isShow=1" class="btn btn-primary">一周内到期标</a>
        <div class="pull-right"><?php echo ($pagebar); ?></div>
    </div>
</div>
    
</div>
<script type="text/javascript" src="__ROOT__/Style/A/js/adminbase.js"></script>
</body>
</html>