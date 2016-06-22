<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="keywords" content="<?php echo ($glo["web_keywords"]); ?>">
	<meta name="description" content="<?php echo ($glo["web_descript"]); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="__ROOT__/Style/H/css/css.css" />
	<link type="text/css" rel="stylesheet" href="/Style/bootstrap-3.3.5/css/bootstrap.min.css"/>
	
	<link type="text/css" rel="stylesheet" href="__ROOT__/Style/JBox/Skins/Currently/jbox.css"/>
	<link href="__ROOT__/Style/H/css/Mbmber.css" rel="stylesheet" type="text/css"/>
	
	<link type="text/css" rel="stylesheet" href="/Style/Font-Awesome/css/font-awesome.min.css"/>
	<script language=javascript type="text/javascript" src="__ROOT__/Style/Js/jquery.js"></script>
	<script language=javascript src="__ROOT__/Style/JBox/jquery.jBox.min.js" type=text/javascript></script>
	<script language=javascript src="__ROOT__/Style/JBox/jquery.jBoxConfig.js" type=text/javascript></script>
	<script  type="text/javascript" src="__ROOT__/Style/Js/ui.core.js"></script>
	<script  type="text/javascript" src="__ROOT__/Style/Js/ui.tabs.js"></script>
	<script type="text/javascript" src="__ROOT__/Style/Js/utils.js"></script>
	<script type="text/javascript">
		function makevar(v){
			var d={};
			for(i in v){
				var id = v[i];
				d[id] = $("#"+id).val();
				if(!d[id]) d[id] = $("input[name='"+id+"']:checked").val();
			}
			return d;
		}

		function ajaxGetData(url,targetid,data){
				if(!url) return;
				data = data||{};
				var thtml = '<div class="loding"><img src="__ROOT__/Style/Js/006.gif"align="absmiddle" />　信息正在加载中...,如长时间未加载完成，请刷新页面</div>';
				$("#"+targetid).html(thtml);
				
				$.ajax({
					url: url,
					data: data,
					timeout: 10000,
					cache: true,
					type: "get",
					dataType: "json",
					success: function (d, s, r) {
						if(d) $("#"+targetid).html(d.html);
					},
					error: '',
					complete: ''
				});
			
		}
		var currentUrl = window.location.href.toLowerCase();
		$(document).ready(function() {
			$('#rotate > ul').tabs();/* 第一个TAB渐隐渐现（{ fx: { opacity: 'toggle' } }），第二个TAB是变换时间（'rotate', 2000） */
			$('.dw_navlist li a').click(function() { // 绑定单击事件
				var nowurl = $(this).attr('href');
				var vid = nowurl.split("#");
				try{
					if(currentUrl.indexOf(vid[0]) != -1 ){
						$('#rotate > ul').tabs('select', "#"+vid[1]); // 切换到第三个选项卡标签
						var geturl= $('#rotate > ul li a [href="#'+vid[1]+'"]').attr("ajax_href");
						ajaxGetData(geturl,vid[1]);
						return false;
					}
				}catch(ex){};
					return true;
			});
			
			$('.ajaxdata a').click(function(){
				var geturl = $(this).attr('ajax_href');
				var hasget = $(this).attr('get')||0;
				var nowurl = $(this).attr('href');
				var vid = nowurl.split("#");
				if(hasget!=1) ajaxGetData(geturl,vid[1]);
				$(this).attr('get','1');
				$('html,body').animate({scorllTop:0},1000);
				return false;
			})
		});
		//ui
	    function addBookmark(title, url) {
	        if (window.sidebar) {
	            window.sidebar.addPanel(title, url, "");
	        }
	        else if (document.all) {
	            window.external.AddFavorite(url, title);
	        }
	        else if (window.opera && window.print) {
	            return true;
	        }
	    }
	    function SetHome(obj, vrl) {
	        try {
	            obj.style.behavior = 'url(#default#homepage)'; obj.setHomePage(vrl);
	            NavClickStat(1);
	        }
	        catch (e) {
	            if (window.netscape) {
	                try {
	                    netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
	                }
	                catch (e) {
	                    alert("抱歉！您的浏览器不支持直接设为首页。请在浏览器地址栏输入“about:config”并回车然后将[signed.applets.codebase_principal_support]设置为“true”，点击“加入收藏”后忽略安全提示，即可设置成功。");
	                }
	                var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
	                prefs.setCharPref('browser.startup.homepage', vrl);
	            }
	        }
	    }
	        $(function() {
	            $(".dw_navlist li,.dv_r_5 li").mousemove(function() {
	                $(this).addClass("current");
	            }).mouseout(function() {
	                $(this).removeClass("current");
	            });
	        });
	</script>

<title><?php echo ($glo["index_title"]); ?></title>
<meta property="wb:webmaster" content="37afd1196b6d28b7" />
<script type="text/javascript" src="__ROOT__/Style/H/js/index.js"></script>
<script type="text/javascript" src="__ROOT__/Style/H/js/common.js" language="javascript"></script>
<script type="text/javascript" src="__ROOT__/Style/H/js/jquery.kinMaxShow-1.0.min.js"></script>
<script type="text/javascript" src="__ROOT__/Style/M/jquery-1.7.1.js"></script>
<meta property="qc:admins" content="30505113364651155636" />
<meta property="wb:webmaster" content="d0d120bc5ee656d7" />
<script type="text/javascript">
	$(function(){
	
		$('#dw_ul li a:eq(0)').attr('class','nowNavItem');
		$('.advance .col-md-3').each(function(index){
			$(this).hover(function(){
				$(this).find('img').attr('src','/Style/H/images/'+index+'1.png');
			},function(){
				$(this).find('img').attr('src','/Style/H/images/'+index+'0.png');
			
			})

		})
		$("#kinMaxShow").kinMaxShow({
				height:400,
				button:{
					showIndex:false,
					normal:{backgroundColor:'#E2E2E2',marginRight:'10px',border:'1px solid silver',right:'46%',bottom:'20px'},
					focus:{backgroundColor:'#FF9966',border:'1px solid silver'}
				},
				callback:function(index,action){
					switch(index){
						case 0 :
								if(action=='fadeIn'){
									$(this).find('.sub_1_1').animate({left:'70px'},600)
									$(this).find('.sub_1_2').animate({top:'60px'},600)
									
								}else{
									$(this).find('.sub_1_1').animate({left:'110px'},600)
									$(this).find('.sub_1_2').animate({top:'120px'},600)
									
								};
								break;
								
						case 1 :
								if(action=='fadeIn'){
									$(this).find('.sub_2_1').animate({left:'-100px'},600)
									$(this).find('.sub_2_2').animate({top:'60px'},600)
								}else{
									$(this).find('.sub_2_1').animate({left:'-160px'},600)	
									$(this).find('.sub_2_2').animate({top:'20px'},600)
								};
								break;
								
						case 2 :
								if(action=='fadeIn'){
									$(this).find('.sub_3_1').animate({right:'350px'},600)
									$(this).find('.sub_3_2').animate({left:'180px'},600)
								}else{
									$(this).find('.sub_3_1').animate({right:'180px'},600)	
									$(this).find('.sub_3_2').animate({left:'30px'},600)
								};
								break;	
					}
				}
			});
	});

</script>
<script type=text/javascript><!--//--><![CDATA[//><!--
function menuFix() {
var ele_ = document.getElementById("nav");
	if(!ele_) return;
var sfEls = ele_.getElementsByTagName("li");
for (var i=0; i<sfEls.length; i++) {
sfEls[i].onmouseover=function() {
this.className+=(this.className.length>0? " ": "") + "sfhover";
}
sfEls[i].onMouseDown=function() {
this.className+=(this.className.length>0? " ": "") + "sfhover";
}
sfEls[i].onMouseUp=function() {
this.className+=(this.className.length>0? " ": "") + "sfhover";
}
sfEls[i].onmouseout=function() {
this.className=this.className.replace(new RegExp("( ?|^)sfhover\\b"),
"");
}
}
}
window.onload=menuFix;
//--><!]]>
</script>
<link rel="stylesheet" type="text/css" href="__ROOT__/Style/H/css/home.css" />
</head><body>
<div class="top-box">
	<div class="container">
		<div class="row top">
			<div class="col-md-6 col-xs-6" style="color:#fff;line-height:30px;">
				<span class="glyphicon glyphicon-phone-alt"></span>
				0512-62853600
			</div>
			<div class="col-md-6 col-xs-6 top-navbar top-navbar-right">
				<ul id="erji">
						<?php if($_SESSION['u_id']): ?><li>
							<a  href="__APP__/member/" style="color:#2AA198">您好，<?php echo session('u_user_name');?></a>
						</li>
						<li>
							<a  href="__APP__/member/msg#fragment-1">
								<span class="glyphicon glyphicon-envelope" style="color:#ff9900;top:3px;"></span>
								<span class="badge"><?php echo (($unread)?($unread):0); ?></span>
							</a>
						</li>		    
						<li><a href="__APP__/member/common/actlogout" class="btn btn-danger">退出</a></li>
						
						<?php else: ?> 
						<li >
							<a href="__ROOT__/member/common/login/" class="btn btn-info">立即登录</a>
						</li>
						<li class="">
							<a href="__ROOT__/member/common/regist/" class="btn btn-danger">免费注册</a>
						</li><?php endif; ?>
					</ul>
			</div>
		</div>
	</div>
</div>
<div class="head-box bg-white">
	<div class="container">
		<nav class="navbar navbar-default row" role="navigation">
			<div class="navbar-header N_logo">
				<button type="button" class="navbar-toggle" data-toggle="collapse" 
		         data-target="#example-navbar-collapse">
		         <span class="sr-only">切换导航</span>
		         <span class="icon-bar"></span>
		         <span class="icon-bar"></span>
		         <span class="icon-bar"></span>
		        </button>
			 	<a href="/"><?php echo get_ad(1);?></a>
			</div>
		   <div class="collapse navbar-collapse" id="example-navbar-collapse">
		      <ul class="nav navbar-nav navbar-right navigation-list" id="dw_ul">
		         <?php $typelist = getTypeList(array('type_id'=>0,'limit'=>9)); foreach($typelist as $vtype=> $va){ ?>
							  <li class="navigation-item "> <a href="<?php echo ($va["turl"]); ?>" class="navigation-item-name"><?php echo ($va["type_name"]); ?>
								<?php $sontypelist = getTypeList(array('type_id'=>$va['id'],'limit'=>8,'notself'=>true)); if($sontypelist != null){ ?>
								<?php } ?>
								</a>
							  </li>
							  <?php } ?>
		      </ul>
		   </div>
		</nav>
	</div>
</div>

<div class="ibannerbox" >
	<div id="kinMaxShow" style="position: relative;">
	<?php $_result=get_ad(4);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$va): $mod = ($i % 2 );++$i;?><div> <a href="<?php echo ($va["url"]); ?>"><img src="__ROOT__/<?php echo ($va["img"]); ?>" /></a> </div><?php endforeach; endif; else: echo "" ;endif; ?>
	
	</div>
	<div class="container">
		<div class="box">
			<p>
				<h3>预期年化收益</h3>
				<span class="high">9.0%-12.0%</span>
			</p>
			<p>
				<h3>超短期限</h3>
				<span class="high">3-30</span>天
			</p>
			<?php if($_SESSION['u_id']): ?><p>
					<a  href="__APP__/member/" class="btn btn-success">您好，<?php echo session('u_user_name');?></a>
				</p>
						
			<?php else: ?> 
				<p>
					<a href="__ROOT__/member/common/regist" class="btn btn-danger">免费注册</a>
				</p>
				<p style="text-align: right;">已有账户？<a href="__ROOT__/member/common/login/" style="color:#8deeee;text-decoration:underline">立即登录</a>
				</p><?php endif; ?>
			
		
		</div>	
	</div>
	
</div>


<div class="advance bg-white">
	<div class="container">
		<h2>我们的优势</h2>
		<p>百财网由百财网络科技（苏州）有限公司开发运营,是 目前苏南地区最安全、最专业的网络信贷理财平台之一。其主打的财日升理财产品期限短,收益高。在这里,投资者可以在短期内获得丰厚回报。</p>
		<div class="row">
			<div class="col-md-3">
				<div><img src="/Style/H/images/00.png"/></div>
				<h4>资金安全</h4>
				<span>
    				项目借款足额抵押物担保,第三方担保机构<br>
    				紧密合作,还款有保障</span>
			</div>
			<div class="col-md-3">
				<div><img src="/Style/H/images/10.png"/></div>
				<h4>收益丰厚</h4>
				<span>年化收益高达10.8%左右,保证<br>投资者短期内获得高回报</span>
			</div>
			<div class="col-md-3">
				<div><img src="/Style/H/images/20.png"/></div>
				<h4>风控完备</h4>
				<span>贷前审查到贷后管理等专业领域都有丰富的<br>经验,务求客户安心、放心、省心</span>
			</div>
			<div class="col-md-3">
				<div><img src="/Style/H/images/30.png"/></div>
				<h4>期限灵活</h4>
				<span>期限3-30天,灵活短期,便于投资者充分,灵<br>活,高效的调度手上闲置资金</span>
			</div>
		</div>
	</div>
</div>


<div class="loanlist">
	<div class="container ">
		<h2>理财项目</h2>
		<?php if(is_array($listLoan["list"])): $i = 0; $__LIST__ = $listLoan["list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vb): $mod = ($i % 2 );++$i;?><div class="row col-xs-10 col-xs-offset-1">
				<div class="panel panel-default">
					<div class="panel-heading" >
						<div class="row">
							<div class="col-xs-1"><img src="/Style/H/images/baicai.png" class="img-circle"></div>
							<div class="col-xs-8 panel-title">
								<a href="__APP__/Loaninvest/detail?id=<?php echo ($vb["id"]); ?>">JK<?php echo ($vb["id"]); echo ($vb["loan_name"]); ?></a></div>
							<div class="col-xs-3">
								<div class="progress progress-striped active">
								   <div class="progress-bar progress-bar-success" role="progressbar" 
								      aria-valuenow="<?php echo (floor($vo["progress"])); ?>" aria-valuemin="0" aria-valuemax="100" 
								      style="width:<?php echo (floor($vb["progress"])); ?>%;">
								      <span ><?php echo (floor($vb["progress"])); ?>%</span>
								   </div>
								</div>
							</div>
						
						</div>
						
					</div>
					<div class="panel-body text-center">
						<div class="col-xs-2">
							<h3 class="text-danger"><?php echo ($vb["interest_rate"]); ?><small>%</small></h3>
							<small>(预期年化)</small>
						</div>
						<div class="col-xs-3">
							<h3 class="text-danger"><?php echo (num_format($vb["loan_amount"])); ?><small>元</small></h3>
							<small>(募集总额)</small>
						</div>
						<div class="col-xs-2">
							<h3 class="text-danger"><small>约</small><?php echo ($vb["loan_duration"]); ?><small><?php echo (duration_format($vb["duration_type"])); ?></small></h3>
							<small>(借款期限)</small>
						</div>
						<div class="col-xs-2">
							<h3><small>利随本清</small></h3>
							<small>(还款方式)</small>
						</div>
						
						<div class="col-xs-3">
							<h3 class="text-danger"><?php echo (num_format($vb["need"])); ?><small>元</small></h3>
							<small>(还需金额)</small>
						</div>
					</div>
					<div class="panel-footer text-right">
						
						<?php if($vb["status"] == 2): ?><a href='__APP__/Loaninvest/detail?id=<?php echo ($vb["id"]); ?>'class="btn btn-info" >借款中</a>
		  				<?php elseif($vb["status"] == 4): ?>
						<a href='__APP__/Loaninvest/detail?id=<?php echo ($vb["id"]); ?>' class="btn btn-success">已满标</a>
						<?php elseif($vb["status"] == 6): ?>
						<a href='__APP__/Loaninvest/detail?id=<?php echo ($vb["id"]); ?>'class="btn btn-success">已满标</a>
		    			<?php elseif($vb["status"] == 7): ?>
						<a href='__APP__/Loaninvest/detail?id=<?php echo ($vb["id"]); ?>' class="btn btn-warning">还款中</a>
		  				<?php elseif($vb["status"] == 8): ?>
						<a href='__APP__/Loaninvest/detail?id=<?php echo ($vb["id"]); ?>'class="btn btn-default">已完成</a><?php endif; ?>

					</div>
				</div>
		</div><?php endforeach; endif; else: echo "" ;endif; ?>
		<div class="row text-center">
			<a href="__APP__/Loaninvest" class="btn btn-warning">点击查看更多</a>
		</div>
		

	</div>
</div>
<div class="news">
	<div class="container">
		<h2>网站公告</h2>
			<?php foreach($noticeList['list'] as $kx => $vn){ ?>
			<a href="<?php echo ($vn["arturl"]); ?>" title="<?php echo ($vn["title"]); ?>" >
				<div class="row">
					<div class="col-xs-1">
						<i class="fa fa-circle-o text-yellow"></i>
					</div>
				 
					<div class="col-xs-8"><?php echo (cnsubstr($vn["title"],18)); ?></div>
					<div class="col-xs-3 text-right"><?php echo (date("Y-m-d ",$vn["art_time"])); ?></div>
				</div>
			</a>
		<?php };$noticeList=NULL; ?>
		<div class="row text-center">
			<a href="/gonggao/index.html" class="btn btn-primary">点击查看更多</a>
		</div>
	</div>
</div>
<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-xs-4 col-xs-offset-1">
                <div class="footer_p">
                <i class="fa  fa-phone-square fa-4x"></i><br><br>
                <span>地址: 苏州工业园区苏雅路318号天翔国际大厦1505室</span><br>
                <span>电话/传真: 0512-62853600</span><br>
                <span>邮箱: service@baicai58.com</span><br>
                <span>邮编: 215123</span><br>
            </div>
            </div>
            <div class="col-xs-4">
                <div class="footer_p">
                <i class="fa  fa-info-circle fa-4x"></i><br><br>
                <span>百财网络科技(苏州)有限公司技术支持</span><br>
                <span>苏ICP备15013328号-1</span>
            </div>
            </div>
            <div class="col-xs-3">
                 <div class="footer_p">
               <i class="fa  fa-weixin fa-4x"></i><br><br>
                <img width="85" height="85" src="../../../../Style/A/images/erweima.png"/>
                </div>
            </div>

        </div>
	    <div class="row text-center" style="border-top:1px dashed #fff;margin-top:10px;padding:10px 0;">

            <a id='___szfw_logo___' href='https://search.szfw.org/cert/l/CX20150626010873010617' target='_blank'><img src='/Style/H/images/cert.jpg'/></a>
            <a href="http://www.baicai58.com/" target="_blank"><img src="/Style/H/images/f4.png" /></a>
            <a href="http://www.baicai58.com/" target="_blank"><img src="/Style/H/images/f5.png" /></a>
            <a href="http://webscan.360.cn/index/checkwebsite/url/www.baicai58.com" target="_blank"><img src="/Style/H/images/f2.png" /></a>
        </div>
    </div>
</div>
</body></html>