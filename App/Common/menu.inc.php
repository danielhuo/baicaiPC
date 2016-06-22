<?php
/*array(菜单名，菜单url参数，是否显示)*/
$i=0;
$j=0;
$menu_left =  array();
$menu_left[$i]=array('全局','#',1);
$menu_left[$i]['low_title'][$i."-".$j] = array('全局设置','#',1);
$menu_left[$i][$i."-".$j][] = array('欢迎页',U('/admin/welcome/index'),1);
$menu_left[$i][$i."-".$j][] = array('网站设置',U('/admin/global/websetting'),1);
$menu_left[$i][$i."-".$j][] = array('友情链接',U('/admin/global/friend'),1);
$menu_left[$i][$i."-".$j][] = array('广告管理',U('/admin/ad/'),1);
$menu_left[$i][$i."-".$j][] = array('导航菜单',U('/admin/navigation/index'),1);
$menu_left[$i][$i."-".$j][] = array('登陆接口',U('/admin/loginonline/'),1);
$menu_left[$i][$i."-".$j][] = array("后台日志",U("/admin/global/adminlog"),1);
$menu_left[$i][$i."-".$j][] = array("自动执行参数",U("/admin/auto/"),1);

$j++;
$menu_left[$i]['low_title'][$i."-".$j] = array('权限管理',"#",1);
$menu_left[$i][$i."-".$j][] = array('管理员管理',U('/admin/Adminuser/'),1);
$menu_left[$i][$i."-".$j][] = array('权限组管理',U('/admin/acl/'),1);

$j++;
$menu_left[$i]['low_title'][$i."-".$j] = array('参数管理','#',1);
$menu_left[$i][$i."-".$j][] = array('业务参数',U('/admin/bconfig/index'),1);
$menu_left[$i][$i."-".$j][] = array('合同资料',U('/admin/hetong/index'),1);
$menu_left[$i][$i."-".$j][] = array('信用级别',U('/admin/leve/index'),1);
$menu_left[$i][$i."-".$j][] = array('投资级别',U('/admin/leve/invest'),1);
$menu_left[$i][$i."-".$j][] = array('年龄别称',U('/admin/age/index'),1);

$j++;
$menu_left[$i]['low_title'][$i."-".$j] = array('缓存管理','#',1);
$menu_left[$i][$i."-".$j][] = array('清空缓存',U('/admin/global/cleanall'),1);
/*借款开始*/
$i++;
$menu_left[$i]= array('借款','#',1);
$menu_left[$i]['low_title'][$i."-".$j] = array('借款列表','#',1);
$menu_left[$i][$i."-".$j][] = array('借款发布',U('/admin/loan/publish'),1);
$menu_left[$i][$i."-".$j][] = array('待初审借款',U('/admin/loan/waitverify'),1);
$menu_left[$i][$i."-".$j][] = array('招标中借款',U('/admin/loan/waitmoney'),1);
$menu_left[$i][$i."-".$j][] = array('待复审借款',U('/admin/loan/waitverify2'),1);
$menu_left[$i][$i."-".$j][] = array('划款中借款',U('/admin/loan/transferring'),1);
$menu_left[$i][$i."-".$j][] = array('欠款账单',U(
	'/admin/loan/repaylist'),1);
$menu_left[$i][$i."-".$j][] = array('还款中借款',U('/admin/loan/repaymenting'),1);
$menu_left[$i][$i."-".$j][] = array('已完成借款',U('/admin/loan/done'),1);
$menu_left[$i][$i."-".$j][] = array('已流标借款',U('/admin/loan/unfinish'),1);
$menu_left[$i][$i."-".$j][] = array('初审未通过',U('/admin/loan/fail'),1);
$menu_left[$i][$i."-".$j][] = array('复审未通过',U('/admin/loan/fail2'),1);
/*借款结束*/
/*配资
$i++;
$menu_left[$i]= array('配资','#',1);
$menu_left[$i]['low_title'][$i."-".$j] = array('借款列表','#',1);
$menu_left[$i][$i."-".$j][] = array('待初审借款',U('/admin/borrow/waitverify'),1);
$menu_left[$i][$i."-".$j][] = array('招标中借款',U('/admin/borrow/waitmoney'),1);
$menu_left[$i][$i."-".$j][] = array('待复审借款',U('/admin/borrow/waitverify2'),1);
$menu_left[$i][$i."-".$j][] = array('划款中借款',U('/admin/borrow/transferring'),1);
$menu_left[$i][$i."-".$j][] = array('追保中借款',U('/admin/borrow/depositadding'),1);
$menu_left[$i][$i."-".$j][] = array('还款中借款',U('/admin/borrow/repaymenting'),1);
$menu_left[$i][$i."-".$j][] = array('已完成借款',U('/admin/borrow/done'),1);
$menu_left[$i][$i."-".$j][] = array('已流标借款',U('/admin/borrow/unfinish'),1);
$menu_left[$i][$i."-".$j][] = array('初审未通过',U('/admin/borrow/fail'),1);
$menu_left[$i][$i."-".$j][] = array('复审未通过',U('/admin/borrow/fail2'),1);
$menu_left[$i][$i."-".$j][] = array('借款异常标',U('/admin/borrow/borrowfull'),1);

$j++;
$menu_left[$i]['low_title'][$i."-".$j] = array("企业直投","#",1);
$menu_left[$i][$i."-".$j][] = array('添加企业直投',U('/admin/tborrow/add'),1);
$menu_left[$i][$i."-".$j][] = array("投资中的借款",U("/admin/tborrow/index"),1);
$menu_left[$i][$i."-".$j][] = array("还款中的借款",U("/admin/tborrow/repayment"),1);
$menu_left[$i][$i."-".$j][] = array("已还完的借款",U("/admin/tborrow/endtran"),1);
$menu_left[$i][$i."-".$j][] = array("已流标的借款",U("/admin/tborrow/liubiaolist"),1);
$j++;
$menu_left[$i]['low_title'][$i."-".$j] = array("定投宝管理","#",1);
$menu_left[$i][$i."-".$j][] = array('添加定投宝',U('/admin/fund/add'),1);
$menu_left[$i][$i."-".$j][] = array("认购中的定投宝",U("/admin/fund/index"),1);
$menu_left[$i][$i."-".$j][] = array("还款中的定投宝",U("/admin/fund/repayment"),1);
$menu_left[$i][$i."-".$j][] = array("已完成的定投宝",U("/admin/fund/endtran"),1);
$j++;
$menu_left[$i]['low_title'][$i."-".$j] = array("债权转让","#",1);
$menu_left[$i][$i."-".$j][] = array('债权转让',U('/admin/debt/index'),1);

$j++;
$menu_left[$i]['low_title'][$i."-".$j] = array('逾期借款','#',1);
$menu_left[$i][$i."-".$j][] = array('逾期统计',U('/admin/expired/detail'),0);
$menu_left[$i][$i."-".$j][] = array('已逾期借款',U('/admin/expired/index'),1);
$menu_left[$i][$i."-".$j][] = array('逾期会员列表',U('/admin/expired/member'),1);

///自动投标会员
$j++;
$menu_left[$i]['low_title'][$i."-".$j] = array('自动投标会员','#',1);
$menu_left[$i][$i."-".$j][] = array('自动投标会员',U('/admin/automembers/index'),1);

///自动投标会员

$i++;
$menu_left[$i]= array('票据','#',1);
$menu_left[$i]['low_title'][$i."-".$j] = array('借款列表','#',1);
$menu_left[$i][$i."-".$j][] = array('待初审票据',U('/admin/bill/waitverify'),1);
$menu_left[$i][$i."-".$j][] = array('招标中票据',U('/admin/bill/waitmoney'),1);
$menu_left[$i][$i."-".$j][] = array('待复审票据',U('/admin/bill/waitverify2'),1);
$menu_left[$i][$i."-".$j][] = array('划款中票据',U('/admin/bill/transferring'),1);
$menu_left[$i][$i."-".$j][] = array('还款中票据',U('/admin/bill/repaymenting'),1);
$menu_left[$i][$i."-".$j][] = array('已完成票据',U('/admin/bill/done'),1);
//预约项目开始
$i++;
$menu_left[$i]= array('预约','#',1);
$menu_left[$i]['low_title'][$i."-".$j] = array('预约列表','#',1);
$menu_left[$i][$i."-".$j][] = array('线下项目列表',U('/admin/reservation/select'),1);
$menu_left[$i][$i."-".$j][] = array('借款预约',U('/admin/reservation/accesstel?type=1'),1);
$menu_left[$i][$i."-".$j][] = array('投资预约',U('/admin/reservation/accesstel?type=2'),1);
//预约项目结束
*/
$i++;
$menu_left[$i]= array('房源','#',1);
$menu_left[$i]['low_title'][$i."-".$j] = array('房源管理','#',1);
$menu_left[$i][$i."-".$j][] = array('房源列表',U('/admin/house/index'),1);

$j++;
$menu_left[$i]['low_title'][$i."-".$j] = array('房源地管理','#',1);
$menu_left[$i][$i."-".$j][] = array('房源地',U('/admin/area/index'),1);

$i++;
$menu_left[$i]= array('会员','#',1);
$menu_left[$i]['low_title'][$i."-".$j] = array('会员管理','#',1);
$menu_left[$i][$i."-".$j][] = array('会员列表',U('/admin/members/index'),1);
$menu_left[$i][$i."-".$j][] = array('会员资料',U('/admin/members/info'),1);
$menu_left[$i][$i."-".$j][] = array('举报信息',U('/admin/jubao/index'),1);

$j++;
$menu_left[$i]['low_title'][$i."-".$j] = array('快捷借款','#',1);
$menu_left[$i][$i."-".$j][] = array('快捷借款',U('/admin/feedback/index'),1);

$j++;
$menu_left[$i]['low_title'][$i."-".$j] = array('认证及申请','#',1);
$menu_left[$i][$i."-".$j][] = array('手机认证',U('/admin/verifyphone/index'),1);
$menu_left[$i][$i."-".$j][] = array('VIP申请',U('/admin/vipapply/index'),1);
$menu_left[$i][$i."-".$j][] = array('实名认证',U('/admin/memberid/index'),1);
$menu_left[$i][$i."-".$j][] = array('额度申请',U('/admin/members/infowait'),1);
$menu_left[$i][$i."-".$j][] = array('上传资料',U('/admin/memberdata/index'),1);

$j++;
$menu_left[$i]['low_title'][$i."-".$j] = array('推荐人管理','#',1);
$menu_left[$i][$i."-".$j][] = array('投资记录',U('/admin/refereedetail/index'),1);
$menu_left[$i][$i."-".$j][] = array('配资记录',U('/admin/refereedetail/index2'),1);


$i++;
$menu_left[$i]= array('充值','#',1);
$menu_left[$i]['low_title'][$i."-".$j] = array('充值管理','#',1);
$menu_left[$i][$i."-".$j][] = array('在线充值',U('/admin/Paylog/paylogonline'),1);
$menu_left[$i][$i."-".$j][] = array('线下充值',U('/admin/Paylog/paylogoffline'),1);
$menu_left[$i][$i."-".$j][] = array('充值记录',U('/admin/Paylog/index'),1);


$j++;
$menu_left[$i]['low_title'][$i."-".$j] = array('提现管理','#',1);
$menu_left[$i][$i."-".$j][] = array('待审核提现',U('/admin/Withdrawlogwait/index'),1);
$menu_left[$i][$i."-".$j][] = array('处理中提现',U('/admin/Withdrawloging/index'),1);
$menu_left[$i][$i."-".$j][] = array('提现已成功',U('/admin/Withdrawlog/withdraw2'),1);
$menu_left[$i][$i."-".$j][] = array('审核未通过',U('/admin/Withdrawlog/withdraw3'),1);
$menu_left[$i][$i."-".$j][] = array('提现总列表',U('/admin/Withdrawlog/index'),1);

$i++;
$menu_left[$i]= array('文章','#',1);
$menu_left[$i]['low_title'][$i."-".$j] = array('文章管理','#',1);
$menu_left[$i][$i."-".$j][] = array('文章列表',U('/admin/article/'),1);
$menu_left[$i][$i."-".$j][] = array('文章分类',U('/admin/acategory/'),1);

/*$i++;
$menu_left[$i]= array('论坛','#',1);
$menu_left[$i]['low_title'][$i."-".$j] = array('论坛管理','#',1);
$menu_left[$i][$i."-".$j][] = array('论坛分类',U('/admin/bbs/type'),1);
$menu_left[$i][$i."-".$j][] = array('帖子列表',U('/admin/bbs/index'),1);
$menu_left[$i][$i."-".$j][] = array('帖子回复',U('/admin/bbs/repay'),1);*/


$i++;
$menu_left[$i]= array('资金','#',1);
$menu_left[$i]['low_title'][$i."-".$j] = array('会员帐户','#',1);
$menu_left[$i][$i."-".$j][] = array('会员帐户',U('/admin/capitalaccount/index'),1);

$j++;
$menu_left[$i]['low_title'][$i."-".$j] = array('充值提现','#',1);
$menu_left[$i][$i."-".$j][] = array('充值记录',U('/admin/capitalonline/charge'),1);
$menu_left[$i][$i."-".$j][] = array('提现记录',U('/admin/capitalonline/withdraw'),1);

$j++;
$menu_left[$i]['low_title'][$i."-".$j] = array('资金变动','#',1);
$menu_left[$i][$i."-".$j][] = array('资金记录',U('/admin/capitaldetail/index'),1);

$j++;
$menu_left[$i]['low_title'][$i."-".$j] = array('投资排行','#',1);
$menu_left[$i][$i."-".$j][] = array('投资排行',U('/admin/capitalrank/index'),1);

$j++;
$menu_left[$i]['low_title'][$i."-".$j] = array('网站统计','#',1);
$menu_left[$i][$i."-".$j][] = array('资金统计',U('/admin/capitalall/index'),1);

$j++;
$menu_left[$i]['low_title'][$i."-".$j] = array('投标记录','#',1);
$menu_left[$i][$i."-".$j][] = array('会员散标',U('/admin/Tender/index'),1);
$menu_left[$i][$i."-".$j][] = array('会员直投',U('/admin/Tender/transfer'),1);

$j++;
$menu_left[$i]['low_title'][$i."-".$j] = array('直投待还款资金','#',1);
$menu_left[$i][$i."-".$j][] = array('直投待还款资金统计',U('/admin/capitalrepay/index'),1);


//`mxl:teamreward`
$i++;
$menu_left[$i]= array('提成','#',1);
$menu_left[$i]['low_title'][$i."-".$j] = array('提成管理',"#",1);
$menu_left[$i][$i."-".$j][] = array('团队长管理',U('/admin/Teamuser/'),1);
$menu_left[$i][$i."-".$j][] = array('经纪人管理',U('/admin/Broker/'),1);
$menu_left[$i][$i."-".$j][] = array('投资人管理',U('/admin/Investor/'),1);
$menu_left[$i][$i."-".$j][] = array('经纪人提成统计',U('/admin/Brokermoney/'),1);
$menu_left[$i][$i."-".$j][] = array('团队长提成统计',U('/admin/Teammoney/'),1);
//`mxl:teamreward`

$i++;
$menu_left[$i]= array('扩展','#',1);
$menu_left[$i]['low_title'][$i."-".$j] = array('数据检测','#',1);
$menu_left[$i][$i."-".$j][] = array('标的数量统计',U('/admin/data/biaonum'),1);
$menu_left[$i][$i."-".$j][] = array('待收/待还查询',U('/admin/data/collect'),1);
$menu_left[$i][$i."-".$j][] = array('资金操作重复',U('/admin/data/paytwo'),1);
$menu_left[$i][$i."-".$j][] = array('问题借款',U('/admin/data/investover'),1);

$j++;
$menu_left[$i]['low_title'][$i."-".$j] = array('充值银行','#',1);
$menu_left[$i][$i."-".$j][] = array('线下充值银行',U('/admin/payoffline/'),1);
$menu_left[$i][$i."-".$j][] = array('线上支付接口',U('/admin/payonline/'),1);

$j++;
$menu_left[$i]['low_title'][$i."-".$j] = array('在线客服','#',1);
$menu_left[$i][$i."-".$j][] = array('客服QQ号',U('/admin/QQ/index'),1);
$menu_left[$i][$i."-".$j][] = array('网站QQ群',U('/admin/QQ/qun'),1);
$menu_left[$i][$i."-".$j][] = array('客服电话',U('/admin/QQ/tel/'),1);

$j++;
$menu_left[$i]['low_title'][$i."-".$j] = array('在线通知','#',1);
$menu_left[$i][$i."-".$j][] = array('信息接口',U('/admin/msgonline/'),1);
$menu_left[$i][$i."-".$j][] = array('信息模板',U('/admin/msgonline/templet/'),1);

$j++;
$menu_left[$i]['low_title'][$i."-".$j] = array('百度云推送管理','#',0);
$menu_left[$i][$i."-".$j][] = array('手机客户端云推送',U('/admin/baidupush/'),1);

$j++;
$menu_left[$i]['low_title'][$i."-".$j] = array('安全检测','#',1);
$menu_left[$i][$i."-".$j][] = array('文件管理',U('/admin/mfields/'),1);
$menu_left[$i][$i."-".$j][] = array('木马查杀',U('/admin/scan/'),1);

$j++;
$menu_left[$i]['low_title'][$i."-".$j] = array('数据库管理','#',1);
$menu_left[$i][$i."-".$j][] = array('备份管理',U('/admin/db/baklist'),1);
$menu_left[$i][$i."-".$j][] = array('清空数据',U('/admin/db/truncate'),1);
$menu_left[$i][$i."-".$j][] = array('数据库信息',U('/admin/db/'),1);

?>

