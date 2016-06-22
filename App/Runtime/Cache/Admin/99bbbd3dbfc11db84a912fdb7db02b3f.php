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
<style >
   
</style>
<div class="container">
    <h4 style="border-bottom:2px solid #CCC;padding-bottom:5px;">F<?php echo ($house["id"]); ?>信息</h4>
    <form class="form-horizontal" action="__URL__/doedit" style="margin-top: 20px;" method="POST">
        <input type="hidden" name="id" value="<?php echo ($house["id"]); ?>">
        <div class="form-group">
            <label class="control-label col-xs-2">标题</label>
            <div class="col-xs-8">
                <input name="title" class="form-control" value="<?php echo ($house["title"]); ?>">
            </div>
        </div>
        <div class="form-group ">
            <label class="control-label col-xs-2">售价</label>
            <div class="col-xs-8">
                <div class="input-group">
                <input name="price" type="number"  class="form-control" value="<?php echo ($house["price"]); ?>">
                <label class="input-group-addon">万元</label>
                </div>
            </div>
        </div>
        <div class="form-group ">
            <label class="control-label col-xs-2">面积</label>
            <div class="col-xs-8">
                <div class="input-group">
                <input name="size" type="number" class="form-control" value="<?php echo ($house["size"]); ?>">
                <label class="input-group-addon">m²</label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-2">装修</label>
            <div class="col-xs-8">
                <select name="decoration" class="form-control">
                    <option value="精装" <?php if(($house["decoration"]) == "精装"): ?>selected<?php endif; ?>>精装</option>
                    <option value="简装" <?php if(($house["decoration"]) == "简装"): ?>selected<?php endif; ?>>简装</option>
                    <option value="毛坯" <?php if(($house["decoration"]) == "毛坯"): ?>selected<?php endif; ?>>毛坯</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-2">朝向</label>
            <div class="col-xs-8">
                <select name="direction" class="form-control">
                    <option value="东" <?php if(($house["direction"]) == "东"): ?>selected<?php endif; ?>>东</option>
                    <option value="南" <?php if(($house["direction"]) == "南"): ?>selected<?php endif; ?>>南</option>
                    <option value="西" <?php if(($house["direction"]) == "西"): ?>selected<?php endif; ?>>西</option>
                    <option value="北" <?php if(($house["direction"]) == "北"): ?>selected<?php endif; ?>>北</option>
                </select>
            </div>
        </div>
        
        <div class="form-group ">
            <label class="control-label col-xs-2">房龄</label>
            <div class="col-xs-8">
                <div class="input-group">
                <input name="house_age" type="number" class="form-control" value="<?php echo ($house["house_age"]); ?>">
                <label class="input-group-addon">年</label>
                </div>
            </div>
        </div>
        <div class="form-group ">
            <label class="control-label col-xs-2">状态</label>
            <div class="col-xs-5">
                <select name="status" class="form-control">
                    <option value="1" <?php if(($house["status"]) == "1"): ?>selected<?php endif; ?>>挂牌</option>
                    <option value="2" <?php if(($house["status"]) == "2"): ?>selected<?php endif; ?>>撤销</option>
                    <option value="3" <?php if(($house["status"]) == "3"): ?>selected<?php endif; ?>>关闭</option>
                </select>
            </div>
        </div>
        <div class="form-group ">
            <label class="control-label col-xs-2">楼层</label>
            <div class="col-xs-8">
                <div class="input-group col-xs-5">
                <input type="number" class="form-control" name="current_floor" value="<?php echo ($house["current_floor"]); ?>">
                <span class="input-group-addon">/</span>
                <input type="number" class="form-control" name="total_floor" value="<?php echo ($house["total_floor"]); ?>">
                </div>
            </div>
        </div>
        <div class="form-group ">
            <label class="control-label col-xs-2">户型</label>
            <div class="col-xs-8">
                <div class="input-group col-xs-5">
                <input type="number" class="form-control" name="bed_room" value="<?php echo ($house["bed_room"]); ?>">
                <span class="input-group-addon">室</span>
                <input type="number" class="form-control" name="live_room" value="<?php echo ($house["live_room"]); ?>">
                <span class="input-group-addon">厅</span>
                <input type="number" class="form-control" name="toilet" value="<?php echo ($house["toilet"]); ?>">
                <span class="input-group-addon">卫</span>
                </div>
            </div>
        </div>
        <div class="form-group ">
            <label class="control-label col-xs-2">区域</label>
            <div class="col-xs-8">
                <div class="input-group col-xs-6">
                <input type="number" class="form-control" name="xian" value="<?php echo ($house["xian"]); ?>">
                <span class="input-group-addon">-</span>
                <input type="number" class="form-control" name="zhen" value="<?php echo ($house["zhen"]); ?>">
                <span class="input-group-addon">-</span>
                <input type="number" class="form-control" name="village" value="<?php echo ($house["village"]); ?>">
                <span class="input-group-addon">-</span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-2">描述</label>
            <div class="col-xs-8">
                <textarea name="describe" class="form-control"><?php echo ($house["describe"]); ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-2">图片</label>
            <div class="col-xs-8">
            <?php if(is_array($house["imgs"])): $i = 0; $__LIST__ = $house["imgs"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$img): $mod = ($i % 2 );++$i;?><div class="col-xs-3">
                        <img src="/<?php echo ($img["url"]); ?>" style="max-width:100%;">
                </div><?php endforeach; endif; else: echo "" ;endif; ?>
             </div>
        </div>
        <div class="form-group">
            <div class="col-xs-5 col-xs-offset-2">
            <input type="submit" class="btn btn-info form-control" value="提交">
            </div>
        </div>
    </form>
</div>
<script type="text/javascript" src="__ROOT__/Style/A/js/adminbase.js"></script>
</body>
</html>