<?php
// 本类由系统自动生成，仅供测试用途
class LoaninvestAction extends HCommonAction {
	/**
    * 车辆抵押列表
    * 
    */
    public function index()
    {
		$parm['map']="l.status in(2,4,6,7,8)";
		$parm['orderby']="l.status ASC,l.id DESC";
	    $parm['pagesize'] =10;
		$parm['limit']=10;
		$list = getLoanList($parm);
		$this->assign("list",$list);
	    $this->display();
    }
    
	/////////////////////////////////////////////////////////////////////////////////////
	
    public function detail()
	{
		$pre = C('DB_PREFIX');
		$id = intval($_GET['id']);
	
	//	$loan = M("loan")->where("id = {$id} and status > 2 ")->find();
		$loan =  M("loan")->find($id);
		$loan['has_collect']=$loan['status']>=7?$loan['loan_amount']:$loan['has_collect'];
		$loan['need'] = $loan['loan_amount'] - $loan['has_collect'];
		$loan['progress'] = getFloatValue($loan['has_collect']/$loan['loan_amount']*100,2);
		
		
		$loan['lefttime'] = $loan['collect_time'] - time();
		$member_info = M('member_info')->find($loan['loan_uid']);
		$member_info['birth'] = substr($member_info['idcard'],6,4)."年".substr($member_info['idcard'],10,2)."月".substr($member_info['idcard'],12,2)."日";
		$area = require C("ROOT_URL")."Webconfig/area.php";
		$member_info['live'] = $area[$member_info['province_now']]."省".$area[$member_info['city_now']]."市".$area[$member_info['area_now']];
		$parm['map'] = "l.loan_id={$id}";
		$parm['orderby'] = "l.invest_time DESC";
		$list = getLoanRecordList($parm);
		$loan_config=require C("APP_ROOT")."Conf/loan_config.php";
		$img_types=$loan_config['img_type'];
		$images[]=M('loan_img')->where("lid={$id} and type=0")->select();
		$images[]=M('loan_img')->where("lid={$id} and type=1")->select();
		$images[]=M('loan_img')->where("lid={$id} and type=2")->select();
		
		
		
		$str_duration = $loan['duration_type'] == 0?"day":"month";
		
		$loan['interest_time'] =  strtotime("+{$loan['collect_day']}"."day",$loan['birth_time']);
		$loan['finish_time'] = strtotime("+{$loan['loan_duration']} ".$str_duration,$loan['interest_time']);
		
		$loan['birth_time'] = date('Y年m月d日',$loan['birth_time']);
		$loan['interest_time']= date('Y年m月d日',$loan['interest_time']);
		$loan['finish_time']  = date('Y年m月d日',$loan['finish_time']);
		
		
		
		$config = require C("APP_ROOT")."Conf/config.php";
		$loan['repay_type']=$config['REPAY_TYPE'][$loan['repay_type']];
		
		$schedule = M("loan_schedule") ->find($id);
		//$count = M("loan_schedule") ->where("id={$id}")->count();
		if(isset($schedule)){
		$schedule['cancel_warrants'] = $schedule['cancel_warrants']==0?0:date('Y年m月d日',$schedule['cancel_warrants']);
		$schedule['fund_trust']= $schedule['fund_trust']==0?0:date('Y年m月d日',$schedule['fund_trust']);
		$schedule['reowner']  =$schedule['reowner']==0?0:date('Y年m月d日',$schedule['reowner']);
		}
		$this->assign("images",$images);
		$this->assign("img_types",$img_types);
		$this->assign("member_info",$member_info);
		$this->assign("loan",$loan);
		$this->assign("list",$list);
		$this->assign("schedule",$schedule);
		$this->display();
    }
	
	public function investcheck(){
		$pre = C('DB_PREFIX');
		if(!$this->uid) {
			ajaxmsg('',1);
			exit;
		}
		$mstatus = M('members_status')->field('id_status,phone_status')->find($this->uid);
		if($mstatus['phone_status']!= 1){
			ajaxmsg('',2);
			exit;
		}
		if($mstatus['id_status']!= 1){
			ajaxmsg('',3);
			exit;
		}
		$mm = M('member_money')->field('account_money')->find($this->uid);
		ajaxmsg($mm['account_money'],0);
	}

	
	public function toinvest(){
		$pre = C('DB_PREFIX');
		$money = intval($_POST['money']);
		$pin = md5($_POST['pin']);
		$loan_id=intval($_POST['id']);
		$status = $_POST['status'];
		
		$id=$this->uid;
		$message=M('members')->where("id={$id}")->find();
		
		$loanmess=M('loan')->where("id={$loan_id}")->find();
		$total=$loanmess['has_collect']+$money;
		if($loanmess['status'] != 2){
			ajaxmsg('不在招标中',9);
			exit;
		}
		if($loanmess['loan_type'] == 3){
			
		$loan_investmess = M('loan_invest')->where("loan_id={$loan_id} and invest_uid ={$id}")->sum("invest_amount");
		$hasinvest = $loan_investmess +	$money;
		
		}
		
		$moneymess = M('member_money')->where("uid={$id}")->find();
		$am = $moneymess['account_money'];
		$mf = $moneymess['money_freeze'];
		
		
		
		$re = '/^[1-9]\d*$/';  
		$date = time();
	
		if($message['pin_pass']==""){
			ajaxmsg('支付密码未设置',1);
			exit;
		}
		if($pin!=$message['pin_pass']){
			ajaxmsg('支付密码不正确',2);
			exit;	
		}
		if($am<$money){
			ajaxmsg('余额不足',3);
			exit;
		}
		if($loanmess['loan_amount']<$total){
			ajaxmsg('购买超限',4);
			exit;
		}
		if($loanmess['loan_uid']==$id){
			ajaxmsg('无法投自己的标',4);
			exit;
		}	
		if(!preg_match($re, $money)){  
			ajaxmsg("请输入正整数",4);  
			exit;		   
		}
		if($date >$loanmess['collect_time']){
			ajaxmsg("募集期限已到",4);
			exit;
			
		}
		if($loanmess['min_invest']>$money){
			ajaxmsg("少于最小投资额",7);	
			exit;
		}	
		
		if($hasinvest >10000){
			ajaxmsg("此类型标单人投资不能超过1万元",8);
			exit;
			
		}
		
		
		$data['loan_id'] = $loan_id;
		$data['invest_uid'] = $id;
		$data['invest_amount'] = $money;
		$data['invest_time'] = time();
		// $data['invest_interest'] = count_profit($money,$loanmess['loan_duration'],$loanmess['duration_type'],$loanmess['interest_rate']);
		 M('loan_invest')->add($data);
		$freeze = memberMoneyLog($id,6,-$money,"对{$loan_id}号{$loanmess['loan_name']}进行投标，冻结金额{$money}元！");
			
		M('loan')->where("id={$loan_id}")->setField('has_collect',$total);
			$loanmess2 = M('loan')->where("id={$loan_id}")->find();
			if($loanmess2['has_collect']==$loanmess2['loan_amount']){
				M('loan')->where("id={$loan_id}")->setField('status',4);	
			}
			
			ajaxmsg("投资成功",0);
	}
}