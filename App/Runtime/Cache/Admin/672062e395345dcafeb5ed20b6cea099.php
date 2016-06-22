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
.lineD dt b{color:#0C0}
</style>
<div class="so_main">
  <div class="page_tit">用户级权限配置</div>
  
  <div class="form2">
  	<form method="post" action="__URL__/doEdit">
	<input type="hidden" name="aid" value="<?php echo ($aid); ?>" />
    <dl class="lineD">
      <dt class="t">用户组名称：</dt>
	  <dd><input type="text" name="groupname" id="groupname" class="input" value="<?php echo ($acldata["groupname"]); ?>" /></dd>
    </dl>
	
	<?php foreach($acl_list as $ke => $vo){ ?>
	
    <dl class="lineD">
      <dt class="t"><b><?php echo ($vo['low_title']['0']); ?></b></dt>
	  <dd>请选择相关权限<input type="checkbox" onclick="select_all('fa<?php echo ($ke); ?>');" id="fa<?php echo ($ke); ?>" /><label for="fa<?php echo ($ke); ?>">全选</label></dd>
    </dl>
	
		<?php foreach($vo['low_leve'] as $fmodel => $vs){ ?>
			<?php foreach($vs as $keyname => $item){ ?>
				<?php if($keyname != "data"){ ?>
		<dl class="lineD">
		  <dt><?php echo ($keyname); ?>：</dt>
		  <dd>
				<?php foreach($item as $itemname => $itemkey){ ?>
				<input data="fa<?php echo ($ke); ?>_son" type="checkbox" name="model[<?php echo ($fmodel); ?>][]" <?php if(is_array($acldata['controller'][$fmodel]) && array_keys($acldata['controller'][$fmodel],$itemkey)) echo 'checked="checked"'; ?> class="check" value="<?php echo ($itemkey); ?>" id="<?php echo ($fmodel); echo ($itemkey); ?>"><label for="<?php echo ($fmodel); echo ($itemkey); ?>"><?php echo ($itemname); ?></label>
				<?php } ?>
		  </dd>
		</dl>
				<?php } ?>
			<?php } ?>
		<?php } ?>
		
	<?php } ?>

    <div class="page_btm">
      <input type="submit" class="btn_b" value="修改" />
    </div>
    </form>
	
  </div>

</div>
<script>
function select_all(id){
	var se = id+"_son";
	if($("#"+id).attr('checked')){
		$("input:[data="+se+"]").each(function(i,obj){
			$(obj).attr('checked','true');
		
		});
	}else{
		$("input:[data="+se+"]").each(function(i,obj){
			$(obj).attr('checked','');
		
		});
	}

}

$(document).ready(function(){
	$(".lineD").mouseover(function(){
			$(this).find(".a_del").css("display","block")
		}
	)
	$(".lineD").mouseleave(function(){
			$(this).find(".a_del").css("display","none")
		}
	)
});

</script>

<script type="text/javascript" src="__ROOT__/Style/A/js/adminbase.js"></script>
</body>
</html>