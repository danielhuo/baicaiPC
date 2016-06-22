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

<title>项目详情</title>
<script language=javascript type="text/javascript" src="__ROOT__/Style/Js/jquery-1.12.1.min.js"></script> 
<script src="__ROOT__/Style/bootstrap-3.3.5/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="__ROOT__/Style/fancybox/jquery.fancybox-1.3.2.css" media="screen" />
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

<style type="text/css">
.panel-title{font-size: 18px;color:#666;}
.tab-content{min-height:300px;background-color: #fff;padding:30px 20px;}

.box{box-shadow:0 0 1px 0px silver;overflow:hidden;height:300px;text-align:center;position:relative;display:table;width:500px;margin:10px 100px 30px;}
.box .pre{
	position:absolute;left:0;height:100%;width:60px;background:#CCC url(/Style/H/images/arrow_left.png) no-repeat center center;
}
.box .next{
	position:absolute;right:0;height:100%;width:60px;background:#CCC url(/Style/H/images/arrow_right.png) no-repeat center center

}
.box .pre:hover,.box .next:hover{
	background-color: #8deeee;

}
.box .pagebar{
	position:absolute;bottom:10px;left:0;width:100%;text-align:center;z-index:1;

}
.box .imgBox{
	display:table-cell;vertical-align:middle;width:50%;overflow:hidden;

}
.box .total{background-color:inherit;}

.panel{margin-top: 20px;}
.panel-warning .fa{
	color:#FF9966;
	margin:20px auto;
}
.panel-warning .fa-angle-right{
	color:#ff9;
}
.panel-body{padding:20px;}
.panel-body .row{height: 50px;}
.form-group{margin-bottom:30px;}
</style>
<script>
	$(function(){
		$('#dw_ul li a:eq(1)').attr('class','nowNavItem');

		/*详情图片开始*/
		var a=<?php echo json_encode($images);?>;
		var totals=new Array();
		var nowIndexs=new Array();
		for (var i = a.length - 1; i >= 0; i--) {
			if(!a[i]) continue;
			totals[i]=a[i].length;
			nowIndexs[i]=0;
		};
		
		$(".box").each(function(index){
			$(this).find(".total").text(totals[index]);
			var that=$(this);
			that.find(".pre,.next").click(function(){
				var der=$(this).attr("class")=="pre"?-1:1;
				nowIndexs[index]=(nowIndexs[index]+der+totals[index])%totals[index];
				that.find(".nowIndex").text(nowIndexs[index]+1);
			    var imgBox=that.find(".imgBox");
			    imgBox.attr("href","__ROOT__/"+a[index][nowIndexs[index]].url);
				imgBox.children("img").attr("src","/"+a[index][nowIndexs[index]].thumb_url);
			})
		})
		/*详情图片结束*/
	    
		$('#investForm').on('show.bs.modal', function () {
  			$login=<?php echo $_SESSION['u_id']?$_SESSION['u_id']:0;?>;
		
			if(!$login){
				alert("请先登录");
	            window.location.href="__APP__/member/common/login";
	            return false;
			}else{
				$.ajax({
					url: "__URL__/investcheck",
					dataType: "json",
					success: function(d) {	 
						if (d.status == 1) {
							alert("请先登录");
						     window.location.href="__APP__/member/common/login";
						}
						else if(d.status == 2)// 无担保贷款多次提醒
						{
							alert("请先手机认证");
						    window.location.href="__APP__/member/verify?id=1#fragment-2";

						}
						else if(d.status == 3)// 无担保贷款多次提醒
						{
						    alert("请先实名认证");
						    window.location.href="__APP__/member/verify?id=1#fragment-3";
						}else{

							$("#account").text(d.message);

						}
					}
				});
			
			}
		}).on('hide.bs.modal', function () {
  			$(this).find('input').val('');
		});
		$("#doinvest").click(function(){
				 var pin = $("#pin").val();				
		         var money = $("#investVal").val();	
		         var id = <?php echo ($loan["id"]); ?>;	
				 var total=$("#account").val();
				 var type =<?php echo ($loan["loan_type"]); ?>;
				 var status = <?php echo ($loan["status"]); ?>;
				if(!money){
					alert("请输入购买金额");  
					return false;
				}
               	var re = /^[1-9]\d*$/;  
				if (!re.test(money)){  
					alert("请输入正整数");  
					return false; ;
				}
				if(!pin){
					alert("请输入支付密码");  
					return false;
				}
			
				$.ajax({
				  	url: "__URL__/toinvest",
				  	type: "post",
				  	dataType: "json",
				  	async: false,
				  	data: {"money":money,'pin':pin,'id':id,'total':total,'status':status},
				 	success: function(d) {
				 		alert(d.message);
						if (d.status == 1) 
							window.location.href="__APP__/member/user#fragment-1";
						else if(d.status == 3)
						 	window.location.href="/member/charge#fragment-1";
						else if(d.status==0)
							window.location.href="__URL__/detail?id=<?php echo ($loan["id"]); ?>";
					  }
				  });
			})		
	})

</script>

<div>
	<div class="modal fade" id="investForm" tabindex="-1" role="dialog" 
	   aria-labelledby="myModalLabel" aria-hidden="true">
	   	<div class="modal-dialog">
	      	<div class="modal-content">
	         	<div class="modal-header">
	           		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
	                	&times;
	            	</button>
	           		 <h4 class="modal-title" id="myModalLabel">投标支付</h4>
	         	</div>
	         	<div class="modal-body form-horizontal" >
	         		<div class="form-group">
	         			<label for="account" class="col-sm-3 col-sm-offset-1 control-label">您的账户金额为 : </label>
						<div class="col-sm-6">
	         				<label id="account" class="amount control-label"></label>
	         			</div>
	         		</div>
	         		<div class="form-group">
	         			<label for="investVal" class="col-sm-3 col-sm-offset-1 control-label">投标金额 :</label>
	         			<div class="col-sm-6">
	         				<input type="text" name="investVal" id="investVal" class="form-control">
	         			</div>
	         			
	         		</div>
	         		<div class="form-group">
	         			<label for="pin" class="col-sm-3 col-sm-offset-1 control-label">支付密码 :</label>
	         			<div class="col-sm-6">
	         				<input type="password" name="pin" id="pin" class="form-control">
	         			</div>
	         		</div>
	        	</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" 
					   data-dismiss="modal">取消
					</button>
					<button type="button" class="btn btn-primary" id="doinvest">
					   提交
					</button>
				</div>
	      	</div>
		</div>
	</div>
	
	<div class="panel panel-danger container">
   		<div class="panel-heading row">
     		<h2 class="panel-title">
         		JK<?php echo ($loan["id"]); ?> <?php echo ($loan["loan_name"]); ?>
      		</h2>
  		</div>
	   	<div class="panel-body row">
			<div class="row">
				<div class="col-md-4 col-xs-4">
					预期年化 : <span class="amount"><?php echo ($loan["interest_rate"]); ?></span> %
				</div>
				<div class="col-md-4 col-xs-4">
					募集金额 : <span class="amount"><?php echo (num_format($loan["loan_amount"])); ?></span> 元
				</div>
				<div class="col-md-4 col-xs-4">
					投资期限 : <small>约</small><span class="amount"><?php echo ($loan["loan_duration"]); ?></span><small>天</small>
				</div>
	      	</div>
	      	<div class="row">
				<div class="col-md-4 col-xs-4">
					还需资金 : <span class="amount"><?php echo (num_format($loan['need'])); ?></span>元
				</div>
				<div class="col-md-4 col-xs-4">
					募集截止 : <span style="color:#CC0000;" id="loan_time" >-- 天 -- 小时 -- 分 -- 秒</span>
				</div>
				<div class="col-md-4 col-xs-4">
					<?php if($loan["status"] != '2'): ?><button type="button" class="btn btn-default">投标已结束</button>
					<?php else: ?>
						<button type="button" data-toggle="modal" 
  						data-target="#investForm" id="toinvest" class="btn btn-info">
   							立即投标
   						</button><?php endif; ?>
				</div>
	      	</div>
	      	<div class="row">
				<div class="col-md-4 col-xs-4">
					投标金额 : <?php echo (num_format($loan["min_invest"])); ?>元起投
				</div>
				<div class="col-md-4 col-xs-4">
					计息方式 : <?php echo ($loan["repay_type"]); ?>
				</div>
	      	</div>
	  	</div>
	</div>
	
	
				
	<div class="panel panel-warning container">	
		<div class="panel-heading row">
     		<h2 class="panel-title">
         		时间轴
      		</h2>
  		</div>
		<div class="panel-body row" style="text-align: center;padding-left:0px;">
			<div class="col-md-2 col-xs-2">
				<i class="fa fa-file-text fa-4x"></i>
				<h5>发布日</h5>
				<span style="font-size: 10px;"><?php echo ($loan["birth_time"]); ?></span>	
			</div>
			<div class="col-md-2 col-xs-2">
				<div class="row">
					<div class="col-md-4 col-xs-4">
						<i class="fa fa-angle-right fa-4x"></i>
					</div>
					<div class="col-md-8 col-xs-8">
						<?php if($loan["status"] >= 6): ?><i class="fa fa-line-chart fa-4x"></i>
						<h5>起息日</h5>
						<span style="font-size: 10px;"><?php echo ($loan["interest_time"]); ?></span>	
						<?php else: ?>
						<i class="fa fa-line-chart fa-4x" style="color:gray;"></i>
						<h5>起息日</h5>
						<span style="font-size: 10px;"><?php echo ($loan["interest_time"]); ?></span><?php endif; ?>
					</div>
					
				</div>
			</div>
			<div class="col-md-2 col-xs-2">
				<div class="row">
					<div class="col-md-4 col-xs-4">
						<i class="fa fa-angle-right fa-4x"></i>
					</div>
					<div class="col-md-8 col-xs-8">
						<?php if($schedule["cancel_warrants"] == null or $schedule["cancel_warrants"] == 0): ?><i class="fa fa-chain-broken fa-4x" style="color:gray;"></i>
					<h5>注销抵押权</h5>	
				<?php else: ?>
					<i class="fa fa-chain-broken fa-4x" ></i>
					<h5>注销抵押权</h5><span style="font-size: 10px;"><?php echo ($schedule["cancel_warrants"]); ?></span><?php endif; ?>
					</div>
					
				</div>
			</div>
			<div class="col-md-2 col-xs-2">
				<div class="row">
					<div class="col-md-4 col-xs-4">
						<i class="fa fa-angle-right fa-4x"></i>
					</div>
					<div class="col-md-8 col-xs-8">
						<?php if($schedule["fund_trust"] == null or $schedule["fund_trust"] == 0): ?><i class="fa fa-exchange fa-4x" style="color:gray;"></i>
					<h5>资金托管</h5>	
				<?php else: ?>
					<i class="fa fa-exchange fa-4x" ></i>	
					<h5>资金托管</h5><span style="font-size: 10px;"><?php echo ($schedule["fund_trust"]); ?></span><?php endif; ?>
					</div>
					
				</div>
			</div>
			<div class="col-md-2 col-xs-2">
				<div class="row">
					<div class="col-md-4 col-xs-4">
						<i class="fa fa-angle-right fa-4x"></i>
					</div>
					<div class="col-md-8 col-xs-8">
						<?php if($schedule["reowner"] == null or $schedule["reowner"] == 0 ): ?><i class="fa fa-file-text fa-4x" style="color:gray;"></i>	
					<h5>过户</h5>	
				<?php else: ?>
					<i class="fa fa-file-text fa-4x" ></i>	
					<h5>过户</h5><span style="font-size: 10px;"><?php echo ($schedule["reowner"]); ?></span><?php endif; ?>
					</div>
					
				</div>
			</div>
			<div class="col-md-2 col-xs-2">
				<div class="row">
					<div class="col-md-4 col-xs-4">
						<i class="fa fa-angle-right fa-4x"></i>
					</div>
					<div class="col-md-8 col-xs-8">
						<?php if($loan["status"] >= 8): ?><i class="fa fa-money fa-4x"></i>
						<h5>收款日</h5>
						<span style="font-size: 10px;"><?php echo ($loan["finish_time"]); ?></span>
						<?php else: ?>
						<i class="fa fa-money fa-4x" style="color:gray;"></i>
						<h5>预期收款日</h5>
						<span style="font-size: 10px;"><?php echo ($loan["finish_time"]); ?></span><?php endif; ?>
					</div>
				</div>
					
			</div>
		</div>	
	</div>		
	
	<div class="container">
		<ul id="myTab" class="nav nav-tabs row">
   			<li class="active"><a href="#home" data-toggle="tab">项目介绍</a></li>
   			<li><a href="#ios" data-toggle="tab">资料审核</a></li>
  			<li><a href="#Java" data-toggle="tab">投资记录</a></li>
		</ul>
		<div id="myTabContent" class="tab-content row">
			<div class="tab-pane fade in active" id="home">
			  <?php echo ($loan["loan_detail"]); ?>
			</div>
			<div class="tab-pane fade" id="ios">
			  <?php if(is_array($images)): $i = 0; $__LIST__ = $images;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$im): $mod = ($i % 2 );++$i; if(!empty($im)): ?><h4><?php echo ($img_types["$key"]); ?></h4>
							<div class="box">
								<a class="pre"></a>
								<a class="imgBox" target="_blank" href="__ROOT__/<?php echo ($im[0]["url"]); ?>">
									<img src="__ROOT__/<?php echo ($im[0]["thumb_url"]); ?>" />
								</a>
								<div class="pagebar">
									<span class="nowIndex">1</span>/<span class="total"></span>
								</div>
								<a class="next"></a>
							</div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
			</div>
	   		<div class="tab-pane fade" id="Java">
	      		<table class="table table-hover">
						<thead>
							<tr>
								<th>投资人</th><th>投资时间</th><th>投资金额</th>
							</tr>
						</thead>
						<tbody>
							<?php if(is_array($list["list"])): $i = 0; $__LIST__ = $list["list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vb): $mod = ($i % 2 );++$i;?><tr >
								<td><?php echo (hidecard($vb["user_name"],4)); ?></td>
								<td><?php echo (date("Y/m/d",$vb["invest_time"])); ?></td>
								<td><?php echo (num_format($vb["invest_amount"])); ?></td>
							</tr><?php endforeach; endif; else: echo "" ;endif; ?> 
							
						</tbody>
						
				</table>
	   		</div>
	   
		</div>
	</div>
	
</div>

<script type="text/javascript">
	var seconds;
	var pers = <?php echo (($loan["progress"])?($loan["progress"]):0); ?>/100;
	var timer=null;
	if (pers >= 1) {
		$("#loan_time").html("募集已经结束！");
		$("#toinvest").hide();
	}else{
		$("#toinvest").hide();
		setLeftTime();
	}
	function setLeftTime() {
		seconds = parseInt(<?php echo ($loan["lefttime"]); ?>, 10);
		timer = setInterval(showSeconds,1000);
	}
	
	function showSeconds() {
		var day1 = Math.floor(seconds / (60 * 60 * 24));
		var hour = Math.floor((seconds - day1 * 24 * 60 * 60) / 3600);
		var minute = Math.floor((seconds - day1 * 24 * 60 * 60 - hour * 3600) / 60);
		var second = Math.floor(seconds - day1 * 24 * 60 * 60 - hour * 3600 - minute * 60);
		if (day1 < 0) {
			clearInterval(timer);
			$("#loan_time").html("募集已经结束！");
			
		} else if (pers >= 1) {
			clearInterval(timer);
			$("#loan_time").html("募集已经结束！");
			
		} else {
			$("#loan_time").html(day1 + " 天 " + hour + " 小时 " + minute + " 分 " + second + " 秒");
				$("#toinvest").show();
			
		}
		seconds--;
	}                
	


</script>

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