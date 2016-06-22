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
	var editUrl = '__URL__/edit';
	var listUrl = '__URL__/listType';
	var addTitle = '添加地区';
	var editTitle = '编辑地区';
</script>
<div class="container">
    <div class="panel panel-primary">
    <div class="panel-heading clearfix">
        <span class="panel-title"><?php echo ($re["name"]); ?></span>
        <div class="btn-group pull-right">
            <a class="btn btn-info" onclick="add(<?php echo ($re["id"]); ?>)">添加地区</a>
            <a class="btn btn-danger" onclick="del()">删除地区</a>
            <a class="btn btn-info" href="__URL__/addmultiple">批量添加</a>
        </div>
    </div>
    <table id="area_list" class="table">
        <thead>
            <tr>
                <th class="text-center">
                    <input type="checkbox" id="checkbox_handle" onclick="checkAll(this)" value="0">
                </th>
                <th>ID</th>
                <th>地区名称</th>
                <th>地区排序</th>
                <th>是否开启子站</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr overstyle='on' id="list_<?php echo ($vo["id"]); ?>" class="leve_1" typeid="<?php echo ($vo["id"]); ?>" parentid="<?php echo ($vo["reid"]); ?>">
                <td class="text-center"><input type="checkbox" name="checkbox" id="checkbox2" onclick="checkon(this)" value="<?php echo ($vo["id"]); ?>"></td>
                <td><?php echo ($vo["id"]); ?></td>
                <td><?php if(($vo["haveson"]) == "true"): ?><span class="typeson typeon" data="son">&nbsp;</span><?php else: ?><span class="typeson">&nbsp;</span><?php endif; echo ($vo["name"]); ?></td>
                <td><?php echo ($vo["sort_order"]); ?></td>
                <td><?php if($vo["is_open"] == '1'): ?>1<?php echo ($vo["doamin"]); else: ?>否<?php endif; ?></td>
                <td>
                    <a href="javascript:void(0);" onclick="edit('?id=<?php echo ($vo['id']); ?>');">编辑</a> 
                    <a href="javascript:void(0);" onclick="del(<?php echo ($vo['id']); ?>);">删除</a> 
                    <a href="__URL__/add?id=<?php echo ($vo["id"]); ?>">添加子栏目</a>   
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
    <div class="panel-footer text-right"><?php echo ($pagebar); ?></div>
</div>
</div>


<script type="text/javascript">
$("#area_list").bind("click", function(event){
	var _this = $(event.target).parent().parent();//获取当前点击元素
	var typeid = $(_this).attr('typeid');
	if(!$($(event.target)).attr("data")) return ;//如果被点击的元素不是span即+-号就不继续执行
	
	var liid = $("#area_list tr").index(_this);//获取当前元素在listtree li下面的元素索引,供传入后传回,以确定在哪个位置插入
	var dir = $(_this).attr('typeid');

	var sontree = $("#area_list tr:[parentid="+typeid+"]");

	//对已获取和没获取的做不同的处理
	if(sontree.html()==null){
		var datas = {'typeid':typeid};
		$.post(listUrl,datas,LTResponse,'json');
		$($(event.target)).addClass("typeoff");
		$($(event.target)).removeClass("typeon");
	}else{
		if(sontree.css("display")=='none'){
			sontree.css("display","");
			$($(event.target)).addClass("typeoff");
			$($(event.target)).removeClass("typeon");
		}else{
			sontree.css("display","none");
			$($(event.target)).addClass("typeon");
			$($(event.target)).removeClass("typeoff");
		}
	}
});
//获取子栏目列表后的处理
function LTResponse(res){
		if (res.status == '0') {
            ui.error(res.data);
        }else{
			$("#area_list tr:[typeid="+res.data.typeid+"]").after(res.data.inner); 
        }
}
</script>
<script type="text/javascript" src="__ROOT__/Style/A/js/adminbase.js"></script>
</body>
</html>