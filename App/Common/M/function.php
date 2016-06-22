<?php
/**
* wap版公共函数库
*/


//获取借款列表
function getBorrowList($parm=array()){
    $map= $parm['map'];
    $orderby= $parm['orderby'];
    
    if($parm['pagesize']){
        //分页处理
        import("ORG.Util.Page");
        $count = M('borrow_info b')
                ->where($map)->count('b.id');
        $p = new Page($count, $parm['pagesize']);
        $page = $p->show();
        $Lsql = "{$p->firstRow},{$p->listRows}";

        $row['page']['total'] = ceil($count/$parm['pagesize']);
        $row['page']['nowPage'] =  isset($_REQUEST['p'])?$_REQUEST['p']:1; 
        //分页处理
    }else{
        $page="";
        $Lsql="{$parm['limit']}";
    }
    $pre = C('DB_PREFIX');
    $suffix=C("URL_HTML_SUFFIX");
    $field = "b.id,b.borrow_name,b.borrow_type,b.updata,b.reward_type,b.borrow_times,b.borrow_status,b.borrow_money,b.borrow_use,b.repayment_type,b.borrow_interest_rate,b.borrow_duration,b.collect_time,b.add_time,b.province,b.has_borrow,b.has_vouch,b.city,b.area,b.reward_type,b.reward_num,b.password,m.user_name,m.id as uid,m.credits,m.customer_name,b.is_tuijian,b.deadline,b.danbao,b.borrow_info,b.risk_control";
    $list = M('borrow_info b')->field($field)->join("{$pre}members m ON m.id=b.borrow_uid")->where($map)->order($orderby)->limit($Lsql)->select();
    $areaList = getArea();
    foreach($list as $key=>$v){
        $list[$key]['location'] = $areaList[$v['province']].$areaList[$v['city']];
        $list[$key]['biao'] = $v['borrow_times'];
        $list[$key]['need'] = $v['borrow_money'] - $v['has_borrow'];
        $list[$key]['leftdays'] = getLeftTime($v['collect_time']);
        $list[$key]['progress'] = getFloatValue($v['has_borrow']/$v['borrow_money']*100,2);
        $list[$key]['vouch_progress'] = getFloatValue($v['has_vouch']/$v['borrow_money']*100,2);
        $list[$key]['burl'] = MU("M/invest","invest",array("id"=>$v['id'],"suffix"=>$suffix));
        $img = unserialize($v['updata']);
        $list[$key]['image'] = $img['0']['img'];
    }
    $row['list'] = $list;
    return $row;
}

function getLoanList($parm=array()){
    $map= $parm['map'];
    $orderby= $parm['orderby'];
    
    if($parm['pagesize']){
        //分页处理
        import("ORG.Util.Page");
        $count = M('Loan l')
                ->where($map)->count('l.id');
        $p = new Page($count, $parm['pagesize']);
        $page = $p->show();
        $Lsql = "{$p->firstRow},{$p->listRows}";

        $row['page']['total'] = ceil($count/$parm['pagesize']);
        $row['page']['nowPage'] =  isset($_REQUEST['p'])?$_REQUEST['p']:1; 
        //分页处理
    }else{
        $page="";
        $Lsql="{$parm['limit']}";
    }
    $pre = C('DB_PREFIX');
    $suffix=C("URL_HTML_SUFFIX");
    $field = "l.id,l.loan_name,l.loan_amount,l.interest_rate,l.loan_duration,l.duration_type,l.has_collect,l.status";
    $list = M('loan l')->field($field)->join("{$pre}members m ON m.id=l.loan_uid")->where($map)->order($orderby)->limit($Lsql)->select();
    $Lconfig = require C("APP_ROOT")."Conf/loan_config.php";
    foreach($list as $key=>$v){
        $v['has_collect']=$list[$key]['status']>=7?$v['loan_amount']:$v['has_collect'];
        $list[$key]['need'] = $v['loan_amount'] - $v['has_collect'];
		//$list[$key]['status2'] = $Lconfig['STATUS'][$v['status']];
        //$list[$key]['leftdays'] = getLeftTime($v['collect_time']);
        $list[$key]['progress'] = getFloatValue($v['has_collect']/$v['loan_amount']*100,2);
        //$list[$key]['burl'] = MU("M/Loaninvest","invest",array("id"=>$v['id'],"suffix"=>$suffix));
		$list[$key]['duration_type'] = duration_format($list[$key]['duration_type']);
		$list[$key]['href'] = loan_status($list[$key]['id'],$list[$key]['status']);
		$list[$key]['need'] = num_format($list[$key]['need']);
		$list[$key]['loan_name'] = cnsubstr($list[$key]['loan_name'],17);
		$list[$key]['loan_amount'] =($list[$key]['loan_amount']/10000).'万';
    }
    $row['list'] = $list;
    return $row;
}



function getInvestList($parm=array()){
    $map= $parm['map'];
    if($parm['pagesize']){
        //分页处理
        import("ORG.Util.Page");
        $count = M('bill_invest bi')->where("bi.invest_uid = {$map['invest_uid']}")->count('bi.id');
        $p = new Page($count, $parm['pagesize']);
        $page = $p->show();
        $Lsql = "{$p->firstRow},{$p->listRows}";
		

        $row['page']['total'] = ceil($count/$parm['pagesize']);
        $row['page']['nowPage'] =  isset($_REQUEST['p'])?$_REQUEST['p']:1; 
        //分页处理
    }else{
        $page="";
        $Lsql="{$parm['limit']}";
    }
    $pre = C('DB_PREFIX');
    $suffix=C("URL_HTML_SUFFIX");
    $field = "b.id bid,b.*,bi.*";
    $list = M('bill b')->field($field)->join("{$pre}bill_invest bi ON bi.bill_id=b.id")->where("bi.invest_uid = {$map['invest_uid']} and b.status = {$map['status']}")->limit($Lsql)->select();
    $row['list'] = $list;
    return $row;
}
function getLoaninvestList($parm=array()){
    $map= $parm['map'];
    $orderby= $parm['orderby'];
    
    if($parm['pagesize']){
        //分页处理
        import("ORG.Util.Page");
        $count =M('loan_invest li')->where($map)->count('li.loan_id');
        $p = new Page($count, $parm['pagesize']);
        $page = $p->show();
        $Lsql = "{$p->firstRow},{$p->listRows}";

        $row['page']['total'] = ceil($count/$parm['pagesize']);
        $row['page']['nowPage'] =  isset($_REQUEST['p'])?$_REQUEST['p']:1; 
        //分页处理
    }else{
        $page="";
        $Lsql="{$parm['limit']}";
    }
    $pre = C('DB_PREFIX');
    $suffix=C("URL_HTML_SUFFIX");
    $field = "l.status,l.interest_rate,l.loan_duration,l.duration_type,l.id as lid,l.loan_name,li.*,m.user_name";
    $list = M('loan_invest li')->field($field)->join("{$pre}loan l on l.id=li.loan_id")->join("{$pre}members m ON m.id=l.loan_uid")->where($map)->order($orderby)->limit($Lsql)->select();
    $Lconfig = require C("APP_ROOT")."Conf/loan_config.php";
    foreach($list as $key=>$v){
        
		$list[$key]['status2'] = $Lconfig['STATUS'][$v['status']];
        $list[$key]['burl'] = MU("M/Loaninvest","invest",array("id"=>$v['lid'],"suffix"=>$suffix));
    }
    $row['list'] = $list;
    return $row;
}
//获取票据列表
function getBillList($parm=array()){
    $map= $parm['map'];
    $orderby= $parm['orderby'];
    
    if($parm['pagesize']){
        //分页处理
        import("ORG.Util.Page");
        $count = M('bill b')
                ->where($map)->count('b.id');
        $p = new Page($count, $parm['pagesize']);
        $page = $p->show();
        $Lsql = "{$p->firstRow},{$p->listRows}";

        $row['page']['total'] = ceil($count/$parm['pagesize']);
        $row['page']['nowPage'] =  isset($_REQUEST['p'])?$_REQUEST['p']:1; 
        //分页处理
    }else{
        $page="";
        $Lsql="{$parm['limit']}";
    }
    $pre = C('DB_PREFIX');
    $suffix=C("URL_HTML_SUFFIX");
    $field = "b.*,m.user_name";
    $list = M('bill b')->field($field)->join("{$pre}members m ON m.id=b.uid")->where($map)->order($orderby)->limit($Lsql)->select();
    $areaList = getArea();
    foreach($list as $key=>$v){
        $list[$key]['need'] = $v['amount'] - $v['has_borrow'];
        $list[$key]['leftdays'] = getLeftTime($v['collect_time']);
        $list[$key]['progress'] = getFloatValue($v['has_borrow']/$v['amount']*100,2);
		$list[$key]['invest_duration'] = (ceil(($v['deadline']-time())/86400)>0)?(ceil(($v['deadline']-time())/86400)):0;
        $list[$key]['burl'] = MU("M/billinvest","billinvest",array("id"=>$v['id'],"suffix"=>$suffix));
    }
    $row['list'] = $list;
    return $row;
}


//获取车辆抵押列表







//获取私募产品列表
function getReservationList($parm=array()){
	//if(empty($parm['map'])) return;
	//$map= $parm['map'];
	$orderby= $parm['orderby'];
	if($parm['pagesize']){
		//分页处理
		import("ORG.Util.Page");
		$count = M('reservation_project r')->count('r.id');
		$p = new Page($count, $parm['pagesize']);
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		 $row['page']['total'] = ceil($count/$parm['pagesize']);
        $row['page']['nowPage'] =  isset($_REQUEST['p'])?$_REQUEST['p']:1; 
		//分页处理
	}else{
		$page="";
		$Lsql="{$parm['limit']}";
	}
	$pre = C('DB_PREFIX');
	$suffix = C("URL_HTML_SUFFIX");

	
	$list = M('reservation_project r')->order($orderby)->limit($Lsql)->select();	
	 foreach($list as $key=>$v){
        $list[$key]['need'] = $v['amount'] - $v['has_borrow'];
		$list[$key]['progress'] = getFloatValue($v['has_borrow']/$v['amount']*100,2);
    }
	$row['list'] = $list;
	
	return $row;
}





//获取record列表
function getRecordList($parm=array()){
	if(empty($parm['map'])) return;
	$map= $parm['map'];
	$orderby= $parm['orderby'];
	if($parm['pagesize']){
		//分页处理
		import("ORG.Util.Page");
		$count = M('bill_invest b')->where($map)->count('b.id');
		$p = new Page($count, $parm['pagesize']);
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理
	}else{
		$page="";
		$Lsql="{$parm['limit']}";
	}
	$pre = C('DB_PREFIX');
	$suffix = C("URL_HTML_SUFFIX");

	
	$list = M('bill_invest b')->field("m.user_name,b.invest_time,b.invest_amount")->join("{$pre}members m ON b.invest_uid = m.id")->where($map)->order($orderby)->limit($Lsql)->select();
	
	$row = array();
	$row['list'] = $list;
	$row['page'] = $page;
	return $row;
}









//获取loanrecord列表
function getLoanRecordList($parm=array()){
	if(empty($parm['map'])) return;
	$map= $parm['map'];
	$orderby= $parm['orderby'];
	if($parm['pagesize']){
		//分页处理
		import("ORG.Util.Page");
		$count = M('loan_invest l')->where($map)->count('l.id');
		$p = new Page($count, $parm['pagesize']);
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理
	}else{
		$page="";
		$Lsql="{$parm['limit']}";
	}
	$pre = C('DB_PREFIX');
	$suffix = C("URL_HTML_SUFFIX");

	
	$list = M('loan_invest l')->field("m.user_name,l.invest_time,l.invest_amount")->join("{$pre}members m ON l.invest_uid = m.id")->where($map)->order($orderby)->limit($Lsql)->select();
	foreach($list as $key=>$v){
        $list[$key]['invest_time'] =date("Y/m/d",$v['invest_time']);
        
    }
	$row = array();
	$row['list'] = $list;
	$row['page'] = $page;
	return $row;
}



/**
* 格式化资金数据保持两位小数
* @desc intval $num  // 接受资金数据
*/
function MFormt($num)
{
    return number_format($num,2);
}


/**
* @param intval $invest_uid // 投资人id  
* @param intval $borrow_id // 借款id
* @param intval $invest_money // 投资金额必须为整数
* @param string $paypass // 支付密码
* @param string $invest_pass='' //投资密码
*/
function checkInvest($invest_uid, $borrow_id, $invest_money, $paypass, $invest_pass='')
{
    $borrow_id = intval($borrow_id);
    $invest_uid = intval($invest_uid);
    if(!$paypass) return(L('please_enter').L('paypass')); 
    if(!$invest_money) return(L('please_enter').L('invest_money'));
    if(!is_numeric($invest_money)) return(L('invest_money').L('only_intval'));
    $vm = getMinfo($invest_uid,'m.pin_pass,mm.account_money,mm.back_money,mm.money_collect');
    
    $pin_pass = $vm['pin_pass'];
    if(md5($paypass) != $pin_pass) return L('paypass').L('error');  // 支付密码错误
    
    if(($vm['account_money']+$vm['back_money'])< $invest_money)
        return L('lack_of_balance');
    
    $borrow = M('borrow_info')
                ->field('id, borrow_uid, borrow_money, has_borrow, has_vouch, borrow_max,borrow_min, 
                            borrow_type, password, money_collect')
                ->where("id='{$borrow_id}'")
                ->find();
    if(!$borrow){ // 没有读取到借款数据
        return L('error_parameter');
    }
    $need = $borrow['borrow_money'] - $borrow['has_borrow'];
    if($borrow['borrow_uid'] == $invest_uid){// 不能投自己的标
        return L('not_cast_their_borrow');
    }
    if(!empty($borrow['password']) && $borrow['password']!= md5($invest_pass)){ // 定向密码
        return L('error_invest_password');
    }
    
    if($borrow['money_collect'] > 0 && $vm['money_collect'] < $borrow['money_collect']){  // 待收限制
        return L('amount_to_be_received');
    }
    
    if($borrow['borrow_min'] > $invest_money ){ // 最小投资
        return L('not_less_than_min').$borrow['borrow_min'].L('yuan');
    }
	if($invest_money%$borrow['borrow_min']){
		return "投标金额必须为最小投资的整数倍！";
	}
    if(($need - $invest_money) < 0 ){ // 超出了借款资金
        return L('error_max_invest_money').$need.L('yuan');
    }
	
    // 避免最后一笔投资剩余金额小于最小资金导致无法投递，再次最后一笔投资可以大于最大投资
    if($invest_money != $need && ($need-$invest_money) < $borrow['borrow_min']){ 
        return L('full_scale_investment').$need.L('yuan'); 
    }
    if($borrow['borrow_max'] && $need > ($borrow['borrow_min']*2) && $invest_money > $borrow['borrow_max']){
        return L('beyond_invest_max'); 
    }
    return 'TRUE';
}
/**
* @param intval $uid  用户id
* @param flaot $money 提现金额
* @param string $paypass 支付密码
*/
function checkCash($uid, $money, $paypass)
{   
    $pre = C('DB_PREFIX'); 
    $uid = intval($uid);
    if(!$money||!$paypass){
        die ('数据不完整'); 
    }
    $paypass = md5($paypass);
    $vo = M('members m')
        ->field('mm.account_money,mm.back_money,(mm.account_money+mm.back_money) all_money,m.user_leve,m.time_limit')
        ->join("{$pre}member_money mm on mm.uid = m.id")
        ->where("m.id={$uid} AND m.pin_pass='{$paypass}'")
        ->find();
    if(!is_array($vo)) return '支付密码不正确';
    
    $datag = get_global_setting(); 
    if($vo['all_money']<$money) return "提现额大于帐户余额";
   
    $start = strtotime(date("Y-m-d",time())." 00:00:00");
    $end = strtotime(date("Y-m-d",time())." 23:59:59");
    $wmap['uid'] = $uid;
    $wmap['withdraw_status'] = array("neq",3);
    $wmap['add_time'] = array("between","{$start},{$end}");
    $today_time = M('member_withdraw')->where($wmap)->count('id');
    $today_money = M('member_withdraw')->where($wmap)->sum('withdraw_money');
    
    $tqfee = explode("|",$datag['fee_tqtx']);
    $fee[0] = explode("-",$tqfee[0]);
    $fee[1] = explode("-",$tqfee[1]);
    $fee[2] = explode("-",$tqfee[2]);
    $one_limit = $fee[2][0]*10000;
    $today_limit = $fee[2][1]/$fee[2][0];  
    if($money < 100 || $money > $one_limit) return "单笔提现金额限制为100-{$one_limit}元";
    if($today_time>=$today_limit){
                return "一天最多只能提现{$today_limit}次";
    }
        if(($today_money+$money)>$fee[2][1]*10000){
            return  "单日提现上限为{$fee[2][1]}万元。您今日已经申请提现金额：{$today_money}元,当前申请金额为:{$money}元,已超出单日上限，请您修改申请金额或改日再申请提现";
        }
        $itime = strtotime(date("Y-m", time())."-01 00:00:00").",".strtotime( date( "Y-m-", time()).date("t", time())." 23:59:59");
        $wmapx['uid'] = $uid;
        $wmapx['withdraw_status'] = array("neq",3);
        $wmapx['add_time'] = array("between","{$itime}");
        $times_month = M("member_withdraw")->where($wmapx)->count("id");
        
    
        $tqfee1 = explode("|",$datag['fee_tqtx']);
        $fee1[0] = explode("-",$tqfee1[0]);
        $fee1[1] = explode("-",$tqfee1[1]);
        if(($money-$vo['back_money'])>=0){
            $maxfee1 = ($money-$vo['back_money'])*$fee1[0][0]/1000;
            if($maxfee1>=$fee1[0][1]){
                $maxfee1 = $fee1[0][1];
            }
            
            $maxfee2 = $vo['back_money']*$fee1[1][0]/1000;
            if($maxfee2>=$fee1[1][1]){
                $maxfee2 = $fee1[1][1];
            }
            
            $fee = $maxfee1+$maxfee2;
            $money = $money-$vo['back_money'];
        }else{
            $fee = $vo['back_money']*$fee1[1][0]/1000;
            if($fee>=$fee1[1][1]){
                $fee = $fee1[1][1];
            }
        }
        
        if(($vo['all_money']-$money - $fee)<0 ){
            
            $moneydata['withdraw_money'] = $money;
            $moneydata['withdraw_fee'] = $fee;
            $moneydata['second_fee'] = $fee;
            $moneydata['withdraw_status'] = 0;
            $moneydata['uid'] =$uid;
            $moneydata['add_time'] = time();
            $moneydata['add_ip'] = get_client_ip();
            $newid = M('member_withdraw')->add($moneydata);
            if($newid){
                memberMoneyLog($uid,4,-$money,"提现,默认自动扣减手续费".$fee."元",'0','@网站管理员@',0);
                MTip('chk6',$uid);
                return 'TRUE';
            } 
            
        }else{
            $moneydata['withdraw_money'] = $money;
            $moneydata['withdraw_fee'] = $fee;
            $moneydata['second_fee'] = $fee;
            $moneydata['withdraw_status'] = 0;
            $moneydata['uid'] =$uid;
            $moneydata['add_time'] = time();
            $moneydata['add_ip'] = get_client_ip();
            $newid = M('member_withdraw')->add($moneydata);
            if($newid){
                memberMoneyLog($uid,4,-$money,"提现,默认自动扣减手续费".$fee."元",'0','@网站管理员@');
                MTip('chk6',$uid);
                return 'TRUE';
            } 
        }
  
    
    return  '申请失败，请重试';
}



function loan_status($id,$status=0)
{   
    switch($status){
        case 0:
            break; 
        case 2: 
			$href = '<a href="'.U('M/Loaninvest/detail', array('id'=>$id)).'"  class="btn btn-info btn-block">我要投资</a> '; 
            break;
        case 4:
        case 6:
			$href = '<a  href="'.U('M/Loaninvest/detail', array('id'=>$id)).'" class="btn btn-gray btn-block">已满标</a> '; 
            break; 
        case 7:
            $href = '<a  href="'.U('M/Loaninvest/detail', array('id'=>$id)).'" class="btn btn-default btn-block">还款中</a> '; 
            break;
        default:
            $href = '<a  href="'.U('M/Loaninvest/detail', array('id'=>$id)).'" class="btn btn-default btn-block">已结束</a> '; 
    }
    
    return $href;
}

?>
