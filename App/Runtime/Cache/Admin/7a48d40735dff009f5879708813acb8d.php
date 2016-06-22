<?php if (!defined('THINK_PATH')) exit();?>
<div class="so_main">

<div class="page_tit">编辑地区</div>
<div class="form2">
	<form method="post" action="__URL__/doEdit" onsubmit="return subcheck();">
	<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
	<div id="tab_1">
	
	<dl class="lineD"><dt>地区名称：</dt><dd><input name="name" id="name"  class="input" type="text" value="<?php echo ($vo["name"]); ?>" ><span id="tip_name" class="tip">*</span></dd></dl>
	<dl class="lineD"><dt>地区排序：</dt><dd><input name="sort_order" id="sort_order"  class="input" type="text" value="<?php echo ($vo["sort_order"]); ?>" ><span id="tip_sort_order" class="tip">数字越大越靠前</span></dd></dl>
	<dl class="lineD"><dt>是否开启子站：</dt><dd><?php $i=0;$___KEY=array ( 0 => '否', 1 => '是', ); foreach($___KEY as $k=>$v){ if(strlen("1key")==1 && $i==0){ ?><input type="radio" name="is_open" value="<?php echo ($k); ?>" id="is_open_<?php echo ($i); ?>" checked="checked" /><?php }elseif(("key1"=="key1"&&$vo["is_open"]==$k)||("key"=="value"&&$vo["is_open"]==$v)){ ?><input type="radio" name="is_open" value="<?php echo ($k); ?>" id="is_open_<?php echo ($i); ?>" checked="checked" /><?php }else{ ?><input type="radio" name="is_open" value="<?php echo ($k); ?>" id="is_open_<?php echo ($i); ?>" /><?php } ?><label for="is_open_<?php echo ($i); ?>"><?php echo ($v); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;<?php $i++; } ?></dd></dl>
	<dl class="lineD"><dt>子站二级域头：</dt><dd><input name="domain" id="domain"  class="input" type="text" value="<?php echo ($vo["domain"]); ?>" ><span id="tip_domain" class="tip">如填eee,则此子站可以用eee.xxx.com访问</span></dd></dl>
	<dl class="lineD"><dt>上级地区：</dt><dd><select name="reid" id="reid"   class="c_select"><option value="">一级地区</option><?php foreach($area_list as $key=>$v){ if("id" && $v["id"]==$vo["reid"]){ ?><option value="<?php echo ($v["id"]); ?>" selected="selected"><?php echo ($v["name"]); ?></option><?php }else{ ?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v["name"]); ?></option><?php }} ?></select><span id="tip_reid" class="tip">一级地区则无父分类</span></dd></dl>
	
	</div><!--tab1-->
	
	<div class="page_btm">
	  <input type="submit" class="btn_b" value="确定" />
	</div>
	</form>
</div>

</div>