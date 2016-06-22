<?php
// 本类由系统自动生成，仅供测试用途
class BillinvestAction extends HCommonAction {
	/**
    * 普通标列表
    * 
    */
    public function index()
    {
       $Bill=M("bill");		
		$parm['map']="b.status in(2,4,7,8)";
		$parm['orderby']="b.id DESC";
	    $parm['pagesize'] = 5;
		$parm['limit']=5;
		$list = getBillList($parm);
		 //dump($list);
		$this->assign("list",$list);
	    $this->display();
    }
    
	/////////////////////////////////////////////////////////////////////////////////////
	
    public function detail()
	{
		$pre = C('DB_PREFIX');
		$id = intval($_GET['id']);
		
		$bill = M("bill")->find($id);
		$bill['need'] = $bill['amount'] - $bill['has_borrow'];
		$bill['lefttime'] =$bill['collect_time'] - time();
		$bill['invest_duration'] = (ceil(($bill['deadline']-time())/86400)>0)?(ceil(($bill['deadline']-time())/86400)):0;
		$bill['progress'] = getFloatValue($bill['has_borrow']/$bill['amount']*100,2);
		$parm['map']="b.bill_id={$id}";
		$parm['orderby']="b.invest_time DESC";
	  //  $parm['pagesize'] = 18;
		//$parm['limit']=18;
		$list = getRecordList($parm);
		$this->assign("bill",$bill);
		$this->assign("list",$list);
	
		$this->display();
    }
	
	public function investcheck(){
		$pre = C('DB_PREFIX');
		if(!$this->uid) {
			ajaxmsg('',1);
			exit;
		}
		$mstatus= M('members_status')->field('id_status,phone_status')->find($this->uid);
		if($mstatus['phone_status']!=1){
			ajaxmsg('',2);
			exit;
		}
		if($mstatus['id_status']!=1){
			ajaxmsg('',3);
			exit;
		}
		$mm=M('member_money')->field('account_money')->find($this->uid);
		ajaxmsg($mm['account_money'],0);
	}

	
	public function toinvest(){
		$pre = C('DB_PREFIX');
		$money = intval($_POST['money']);
		$pin = md5($_POST['pin']);
		$bill_id=intval($_POST['id']);
		$id=$this->uid;
		$message=M('members')->where("id={$id}")->find();
		
		$billmess=M('bill')->where("id={$bill_id}")->find();
		$total=$billmess['has_borrow']+$money;
		
		$moneymess=M('member_money')->where("uid={$id}")->find();
		$am=$moneymess['account_money'];
		$mf=$moneymess['money_freeze'];
		
		
		
		 $re = '/^[1-9]\d*$/';  
		if(!preg_match($re, $money)) 
		{  
				 ajaxmsg("请输入正整数",6);  
				exit;
				   }
		
		
		if($message['pin_pass']==""){
			ajaxmsg('支付密码未设置',1);
			exit;
		}
		if($pin!=$message['pin_pass']){
			ajaxmsg('密码不正确',2);
			exit;
			
		}
		if($am<$money){
			ajaxmsg('余额不足',3);
			exit;
		}
		if($billmess['amount']<$total){
			ajaxmsg('购买超限',4);
			exit;
		}
		
		
		if($billmess['uid']==$id){
			ajaxmsg('无法投自己的标',5);
			exit;
		}
		
		
		
		
		
		
		$data['bill_id']=$bill_id;
		$data['invest_uid']=$id;
		$data['invest_amount']=$money;
		$data['invest_time'] = time();
		$data['invest_duration'] = ceil(($billmess['deadline']-$data['invest_time'])/86400);
		$data['invest_interest'] = $data['invest_amount'] * $billmess['interest_rate']/100 * $data['invest_duration']/360;
		M('bill_invest')->add($data);
		
		
		
	//	$data['account_money']=$am-$money;
	//	$data['money_freeze']=$mf+$money;
	//	M('member_money')->where("uid={$id}")->save($data);
		$freeze = memberMoneyLog($id,6,-$money,"对{$bill_id}号标进行投标，冻结金额{$money}元！");
		
		
		
		M('bill')->where("id={$bill_id}")->setField('has_borrow',$total);
			$billmess2=M('bill')->where("id={$bill_id}")->find();
			if($billmess2['has_borrow']==$billmess2['amount']){
				M('bill')->where("id={$bill_id}")->setField('status',4);
				
			}
			
			ajaxmsg("投资成功",7);
		
		
		

		
		
		
		
	}
	
	
	
	
	
	
}