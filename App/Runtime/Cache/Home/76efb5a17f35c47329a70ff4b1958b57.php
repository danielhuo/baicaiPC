<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
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

<TITLE>新手指引</TITLE>
<script language=javascript type="text/javascript" src="__ROOT__/Style/Js/jquery-1.12.1.min.js"></script> 
<script src="__ROOT__/Style/bootstrap-3.3.5/js/bootstrap.min.js"></script>
<style type="text/css">

.tab-pane{
	margin:0 auto;
	padding:20px 0 30px; 
	min-height:600px;
}
.tab-pane h4{
	color:#000;
}


.tabs-left > .nav-tabs > li{
float: none;
text-align: center;
}
.tabs-left > .nav-tabs > li > a{
  margin-right: -1px;
  -webkit-border-radius: 4px 0 0 4px;
     -moz-border-radius: 4px 0 0 4px;
          border-radius: 4px 0 0 4px;color: #000;
}

.tabs-left > .nav-tabs> li.active > a,
.tabs-left > .nav-tabs> li.active > a:hover,
.tabs-left > .nav-tabs> li.active > a:focus {
  
  border-right:1px solid #fff;color: #ddd;font-weight: bold;
}

</style>
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

<div class="cms_page">
	<div class="container ">
		<div class="row">
			<div class="nav col-xs-2">
			 	<ul class="nav nav-pills nav-stacked" >
					<li class="active"><a href="#register" data-toggle="tab">注册充值</a></li>
					<li class=""><a href="#safe" data-toggle="tab">账户安全</a></li>
					<li class="">  <a href="#invest" data-toggle="tab">项目投资</a></li>
				</ul>
			</div>
			<div class="col-md-10  col-xs-10 cms_content tab-content " id="myTabContent">
				<div class="tab-pane fade in active " id="register">
					<p>
						<h4>1. 百财网如何注册，充值?</h4>方式一：请您登陆百财网（www.baicai58.com）,在右上方点击【免费注册】对话框，填写用户注册信息，完成注册。<br>方式二：请您关注百财网微信公众号（baicai58-com）或者至接搜索“百财网”点击关注，在手机客户端点击【免费注册】对话框，填写用户注册信息，完成注册。
					</p>
					<hr>
					<p>
						<h4>2. 如何进行身份验证?</h4>进入【我的账户】点击【账户资料设置】，完成手机验证、电子邮箱验证、实名认证。
					</p>
					<hr>
					<p>
						<h4>3. 如何绑定银行卡?</h4>通过实名认证后，绑定提现银行卡，输入相关信息即可。百财网目前仅支持20家银行提现：中国银行、中国农业银行、中国工商银行、中国建设银行、中国邮政储蓄银行、广发银行、光大银行、中国交通银行、招商银行、兴业银行、平安银行（深发展）、中信银行、民生银行、浦发银行、华夏银行、北京银行、上海银行、上海农商银行、北京农商银行。
					</p>
					<hr>
					<p>
						<h4>4. 银行卡充值必须开通网银吗？</h4>银行卡充值，不同银行有不同的标准，目前大部分银行是需要开通网银功能也可以进行验证的，如有疑问可以在线咨询客服。
					</p>
					<hr>
					<p>
						<h4>5. 百财有哪些充值渠道？</h4>目前百财网有1个第三方支付接口，可以通过百财网站选择“宝付支付”进行充值。
					</p>
					<hr>
					<p>
						<h4>6. 在线充值具体如何操作？</h4>PC端登陆“我的账户”，点击“账户充值”-“在线充值”页面--填写“充值金额”-点击“登陆到网银充值”跳转到第三方支付平台-“宝付支付界面”在直接跳转到银行个人网银支付页面。在跳转页面后按步骤填写信息完成充值。
					</p>
					<hr>
					<p>
						<h4>7. 百财网投资后，如何提现？</h4>用户投资百财网相应的标的后，待该标到期，可以申请提现。提现申请提交后，财务部门会尽快审核处理。
					</p>
					<hr>
					<p>
						<h4>8. 提现账户可以绑定多张银行卡吗？</h4> 提现账号只能绑定一张银行卡，且必须与您注册实名一致。
					</p>
					<hr>
					<p>
						<h4>9. 百财网什么时间段处理提现？</h4> 工作日当天下午5点前的提现申请，财务当天处理；下午5点后的提现顺延至第2个工作日处理； 周六、周日及国家节假日不处理提现。到账时间视银行为准。
					</p>
					<hr>
					<p>
						<h4>10.提现需要手续费吗？</h4> 百财网用户提现免收费用。
					</p>
					<hr>
					<p>
						<h4>11.百财网提现额度限制是多少？</h4>用户提现单笔最高金额50万，当日累计金额无限制，超过100万提现建议提前与客服联系。
					</p>
				
				</div>
				<div class="tab-pane fade" id="safe">
					<p>
						<h4>1. 如何修改登陆账号密码？</h4>　登陆百财网，打开【我的账户】点击【安全信息】方框，点击修改登陆密码，输入原密码及新的密码并确认即完成修改登陆密码。
					</p>
					<hr>
					<p>	
						<h4>2. 如何设置支付密码？</h4>　　登陆百财网，打开【我的账户】点击【安全信息】方框，点击修改支付密码：可以通过旧密码修改输入原支付密码及新的支付密码并确认即可完成支付密码修改。也可通过短信验证码方式修改支付密码。
					</p>
				</div>
				<div class="tab-pane fade" id="invest">
					<p>
						<h4>1. 如何在百财网进行投资？</h4>只要注册为百财网正式用户，完善各项注册信息（实名认证，手机认证，邮箱认证），填写绑定提现银行卡，通过百财网第三方充值渠道进行充值后即可投资。
					</p>
					<hr>
					<p>
						<h4>2. 投资后是否可以取消？</h4>您在项目投资后不能取消。投资项目在募资完成后，您账户上投标的冻结金额将自动转入到该投资项目借款人的操盘账户中。若此投资项目募资失败，则您账户上的投资冻结金额将自动转为您的可用金额。
					</p>
					<hr>
					<p>
						<h4>3. 投资详情在哪里可以看到？</h4>登陆账户，点击【我的账户】即可查询账户总资产、可用余额、冻结金额、待收总额、待还总额等信息。
					</p>
					<hr>
					<p>
						<h4>4. 百财网标的类型有哪些？不同标的收益计算方法都不同吗？</h4>目前，百财网的标的可以从两个方面进行分类：<br>  ①　从标的期限上，百财网目前有3-30天、1-90天、1-180天三种期限的投资标，并且今后会根据业务情况调整或推出其他期限的投资标；<br> ②　从标的类型上，百财网有赎楼贷对应的超短期的理财产品【财日升】、车辆抵押对应的短期理财产品【车抵贷】、票据融资相关的中短期理财产品【票小宝】运营过程中，我们会根据业务情况选择不同的标的发布。 
					</p>
					<hr>
					<p>
						<h4>5. 如何获得投资收益？</h4>在投资项目募资成功到期借款人按时还本付息。利息收益将随本金进入投资人的账户中，投资人可以随时再投资或者提款取出。
					</p>
					<hr>
					<p>	
						<h4>6. 百财网如何保障投资者的投资收益？</h4>百财网有全方位安全保障，信息公开透明等如下机制保障您的投资收益：<br> ①足额抵押：深耕于足额抵押的金融细分市场，所有借款标的均有相应足额的抵押物以保障最终还款来源；<br>②百财网有专业的风控团队，标准的风控流程体系，对每一个借款标的进行征信调查、实地考察、财务核查、抵押物评估，能有效地规避业务风险；<br>③第三方支付平台：百财网的注册投资人充值资金全部进入支付平台的托管账户，满标后，百财网向支付平台发出支付指令直接划付至借款人账户；借款人还款也同样通过支付平台的账户按平台结算指令还款。
					</p>
					<hr>
					<p>
						<h4>7. 如何获得推广奖励？</h4>百财网注册用户邀请好友填写推荐码注册，你就成为百财网推广合伙人，合伙人奖励根据好友投资金额的千分之一（上限300元）标满即返现，零门槛做推广合伙人，随时拿分红。
					</p>
					<hr>
					<p>
						<h4>8.百财网有哪些推广方式？</h4>①用户可以通过微信二维码进行邀请；<br>
						②用户可以通过复制邀请链接发送给好友。
					</p>
				</div>
				
			</div>
		</div>
	</div>
	
</div>

<script type="text/javascript">
	$(function(){
		$('#dw_ul li a:eq(3)').attr('class','nowNavItem');
		$('.box_menu').each(function(index){
			$(this).click(function(){
				$('.box_left_ul:eq('+index+')').slideDown().siblings('.box_left_ul').slideUp();
			})
		})
		$('.box_right li').each(function(index){
			$(this).click(function(){
				$(this).addClass("nowR").siblings().removeClass("nowR");
				$('.box_main:eq('+index+')').show().siblings('.box_main').hide();
			})
		})
	
	})

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