<?php
// 本类由系统自动生成，仅供测试用途
class AgreementAction extends MCommonAction {
	public function borrow(){
		$this->display();
	}
	public function index(){
		$invest_id =$_GET['invest_id'];
		$loan_invest = M('loan_invest')->where("id = {$invest_id}")->find();
		$loan = M('loan l')->join("{$this->pre}loan_deal ld ON l.id=ld.id")->where("l.id = {$loan_invest['loan_id']}")->find();
		$mLoan = M("members m")->join("{$this->pre}member_info mi ON mi.uid=m.id")->field('mi.real_name,mi.idcard,m.user_name')->where("m.id={$loan['loan_uid']}")->find();
		$mInvest = M("members m")->join("{$this->pre}member_info mi ON mi.uid=m.id")->field('mi.real_name,mi.idcard,m.user_name')->where("m.id={$loan_invest['invest_uid']}")->find();
		$bid = 'PBB';
		$invest_id =  substr($invest_id + 10000,1);
		$this->assign('bid',$bid);
		$this->assign('invest_id',$invest_id);
		$this->assign('loan_invest',$loan_invest);
		$this->assign('loan',$loan);
		$this->assign('mLoan',$mLoan);
		$this->assign('mInvest',$mInvest);
		$this->display("loan_{$loan['loan_type']}");
		
		
	}
	public function downfile(){
		//$borrow_config = require C("APP_ROOT")."Conf/borrow_config.php";
		$invest_id=intval($_GET['invest_id']);
		//$borrow_id=intval($_GET['id']);
		
		$iinfo = M('borrow_investor')->field('id,borrow_id,investor_capital,investor_interest,deadline,investor_uid,add_time')->where("id={$invest_id}")->find();
		$binfo = M('borrow_info')->field('id,repayment_type,borrow_duration,borrow_fee,borrow_uid,borrow_deposit,borrow_interest,borrow_type,borrow_use,borrow_money,full_time,add_time,borrow_interest_rate,deadline,second_verify_time')->find($iinfo['borrow_id']);
		$mBorrow = M("members m")->join("{$this->pre}member_info mi ON mi.uid=m.id")->field('mi.real_name,mi.idcard,m.user_name')->where("m.id={$binfo['borrow_uid']}")->find();
		$mInvest = M("members m")->join("{$this->pre}member_info mi ON mi.uid=m.id")->field('mi.real_name,mi.idcard,m.user_name')->where("m.id={$iinfo['investor_uid']}")->find();
		if(!is_array($iinfo)||!is_array($binfo)||!is_array($mBorrow)||!is_array($mInvest)) exit;
		
		$textcordon=M('global')->field('text')->where("id=117")->find();//警戒线
		$textunwind=M('global')->field('text')->where("id=118")->find();//平仓线
		$ctext=(int)$textcordon['text'];
		$wtext=(int)$textunwind['text'];
		$money=(int)$binfo['borrow_money'];
		$cordon=$ctext*$money/100;
		$unwind=$wtext*$money/100;
		$this->assign('cordon', $cordon);
		$this->assign('unwind', $unwind);
		
		    $name=$mBorrow['real_name'];
			$name=mb_substr($name,0,1,'utf-8').'**';
			$this->assign('name', $name);
			$card_s=$mBorrow['idcard'];
			$card_s=substr($card_s,0,6).'********'.substr($card_s,14,4);
			$this->assign('card_s', $card_s);
			
		$detail = M('investor_detail d')->field('d.borrow_id,d.investor_uid,d.borrow_uid,d.interest,d.capital,sum(d.capital+d.interest-d.interest_fee) benxi,d.total')->where("d.borrow_id={$iinfo['borrow_id']} and d.invest_id ={$iinfo['id']}")->group('d.investor_uid')->find();
		$detailinfo = M('investor_detail d')->field('d.borrow_id,d.investor_uid,d.borrow_uid,(d.capital+d.interest-d.interest_fee) benxi,d.capital,d.interest,d.interest_fee,d.sort_order,d.deadline')->where("d.borrow_id={$iinfo['borrow_id']} and d.invest_id ={$iinfo['id']}")->select();
		
		
		$time = M('borrow_investor')->field('id,add_time')->where("borrow_id={$iinfo['borrow_id']} order by add_time asc")->limit(1)->find();
		
		if($binfo['repayment_type']==1){
				$deadline_last = strtotime("+{$binfo['borrow_duration']} day",$time['add_time']);
			}else{
				$deadline_last = strtotime("+{$binfo['borrow_duration']} month",$time['add_time']);
			}
		$this->assign('deadline_last',$deadline_last);
		$this->assign('detailinfo',$detailinfo);
		$this->assign('detail',$detail);
        
		$type1 = $this->gloconf['BORROW_USE'];
		$binfo['borrow_use'] = $type1[$binfo['borrow_use']];
		$ht=M('hetong')->field('hetong_img,name,dizhi,tel')->find();
		
		$this->assign("ht",$ht);
		$type = $borrow_config['REPAYMENT_TYPE'];
		//echo $binfo['repayment_type'];
		$binfo['repayment_name'] = $type[$binfo['repayment_type']];

		$iinfo['repay'] = getFloatValue(($iinfo['investor_capital']+$iinfo['investor_interest'])/$binfo['borrow_duration'],2);
		
		$this->assign("bid","bytp2pD");
		//print_r($type);
		$this->assign('iinfo',$iinfo);
		$this->assign('binfo',$binfo);
		$this->assign('mBorrow',$mBorrow);
		$this->assign('mInvest',$mInvest);
      
		$detail_list = M('investor_detail')->field(true)->where("invest_id={$invest_id}")->select();
		$this->assign("detail_list",$detail_list);
		
		$this->display("index");
		
    }
	public function apply(){
		$invest_id =$_GET['invest_id'];
		$bill_invest = M('bill_invest')->where("id = {$invest_id}")->find();
		$bill = M('bill')->where("id = {$bill_invest['bill_id']}")->find();
		$mBill = M("members m")->join("{$this->pre}member_info mi ON mi.uid=m.id")->join("{$this->pre}company c ON c.uid=m.id")->field('mi.real_name,mi.idcard,m.user_name,c.office_address')->where("m.id={$bill['uid']}")->find();
		$mInvest = M("members m")->join("{$this->pre}member_info mi ON mi.uid=m.id")->field('mi.real_name,mi.idcard,m.user_name')->where("m.id={$bill_invest['invest_uid']}")->find();
		$bid = 'PBB';
		$invest_id =  substr($invest_id + 10000,1);
		$this->assign('bid',$bid);
		$this->assign('invest_id',$invest_id);
		$this->assign('bill_invest',$bill_invest);
		$this->assign('bill',$bill);
		$this->assign('mBill',$mBill);
		$this->assign('mInvest',$mInvest);
		$this->display();
	}
	public function downfile1(){
		//$borrow_config = require C("APP_ROOT")."Conf/borrow_config.php";
		$invest_id=intval($_GET['id']);
		//$borrow_id=intval($_GET['id']);
		
		$iinfo = M('borrow_investor')->field('id,borrow_id,investor_capital,investor_interest,deadline,investor_uid,add_time')->where("(investor_uid={$this->uid} OR borrow_uid={$this->uid}) AND id={$invest_id}")->find();
		$binfo = M('borrow_info')->field('id,repayment_type,borrow_duration,borrow_fee,borrow_uid,borrow_deposit,borrow_interest,borrow_type,borrow_use,borrow_money,full_time,add_time,borrow_interest_rate,deadline,second_verify_time')->find($iinfo['borrow_id']);
		$mBorrow = M("members m")->join("{$this->pre}member_info mi ON mi.uid=m.id")->field('mi.real_name,mi.idcard,m.user_name')->where("m.id={$binfo['borrow_uid']}")->find();
		$mInvest = M("members m")->join("{$this->pre}member_info mi ON mi.uid=m.id")->field('mi.real_name,mi.idcard,m.user_name')->where("m.id={$iinfo['investor_uid']}")->find();
		if(!is_array($iinfo)||!is_array($binfo)||!is_array($mBorrow)||!is_array($mInvest)) exit;
		
		$textcordon=M('global')->field('text')->where("id=117")->find();//警戒线
		$textunwind=M('global')->field('text')->where("id=118")->find();//平仓线
		$ctext=(int)$textcordon['text'];
		$wtext=(int)$textunwind['text'];
		$money=(int)$binfo['borrow_money'];
		$cordon=$ctext*$money/100;
		$unwind=$wtext*$money/100;
		$this->assign('cordon', $cordon);
		$this->assign('unwind', $unwind);
		
		    $name=$mBorrow['real_name'];
			$name=mb_substr($name,0,1,'utf-8').'**';
			$this->assign('name', $name);
			$card_s=$mBorrow['idcard'];
			$card_s=substr($card_s,0,6).'********'.substr($card_s,14,4);
			$this->assign('card_s', $card_s);
			
		$detail = M('investor_detail d')->field('d.borrow_id,d.investor_uid,d.borrow_uid,d.interest,d.capital,sum(d.capital+d.interest-d.interest_fee) benxi,d.total')->where("d.borrow_id={$iinfo['borrow_id']} and d.invest_id ={$iinfo['id']}")->group('d.investor_uid')->find();
		$detailinfo = M('investor_detail d')->field('d.borrow_id,d.investor_uid,d.borrow_uid,(d.capital+d.interest-d.interest_fee) benxi,d.capital,d.interest,d.interest_fee,d.sort_order,d.deadline')->where("d.borrow_id={$iinfo['borrow_id']} and d.invest_id ={$iinfo['id']}")->select();
		$time = M('borrow_investor')->field('id,add_time')->where("borrow_id={$iinfo['borrow_id']} order by add_time asc")->limit(1)->find();
		
		if($binfo['repayment_type']==1){
				$deadline_last = strtotime("+{$binfo['borrow_duration']} day",$time['add_time']);
			}else{
				$deadline_last = strtotime("+{$binfo['borrow_duration']} month",$time['add_time']);
			}
		$this->assign('deadline_last',$deadline_last);
		$this->assign('detailinfo',$detailinfo);
		$this->assign('detail',$detail);
        
		$type1 = $this->gloconf['BORROW_USE'];
		$binfo['borrow_use'] = $type1[$binfo['borrow_use']];
		$ht=M('hetong')->field('hetong_img,name,dizhi,tel')->find();
		
		$this->assign("ht",$ht);
		$type = $borrow_config['REPAYMENT_TYPE'];
		//echo $binfo['repayment_type'];
		$binfo['repayment_name'] = $type[$binfo['repayment_type']];

		$iinfo['repay'] = getFloatValue(($iinfo['investor_capital']+$iinfo['investor_interest'])/$binfo['borrow_duration'],2);
		
		$this->assign("bid","bytp2pD");
		//print_r($type);
		$this->assign('iinfo',$iinfo);
		$this->assign('binfo',$binfo);
		$this->assign('mBorrow',$mBorrow);
		$this->assign('mInvest',$mInvest);
      
		$detail_list = M('investor_detail')->field(true)->where("invest_id={$invest_id}")->select();
		$this->assign("detail_list",$detail_list);
		
		$this->display("index");
		
    }
	
	 public function downliuzhuanfile(){
		$borrow_config = require C("APP_ROOT")."Conf/borrow_config.php";
		$type = $borrow_config['REPAYMENT_TYPE'];

		$invest_id=intval($_GET['id']);
		
		$iinfo = M("transfer_borrow_investor")->field(true)->where("investor_uid={$this->uid} AND id={$invest_id}")->find();

		$binfo = M('transfer_borrow_info')->field(true)->find($iinfo['borrow_id']);
		$tou =  M('transfer_investor_detail')->where(" borrow_id={$iinfo['borrow_id']} AND investor_uid={$this->uid} ")->find();
		
		$mBorrow = M("members m")->join("{$per}member_info mi ON mi.uid=m.id")->field('mi.real_name,m.user_name')->where("m.id={$binfo['borrow_uid']}")->find();
		$mInvest = M("members m")->join("{$per}member_info mi ON mi.uid=m.id")->field('mi.real_name,m.user_name')->where("m.id={$iinfo['investor_uid']}")->find();
		
		if(!is_array($tou)) $mBorrow['real_name'] = hidecard($mBorrow['real_name'],5);

		$binfo['repayment_name'] = $type[$binfo['repayment_type']];

		$this->assign("bid","LZBHT-".str_repeat("0",5-strlen($binfo['id'])).$binfo['id']);
		
		$detailinfo = M('transfer_investor_detail d')->join("{$per}transfer_borrow_investor bi ON bi.id=d.invest_id")->join("{$per}members m ON m.id=d.investor_uid")->field('d.borrow_id,d.investor_uid,d.borrow_uid,d.capital,sum(d.capital+d.interest-d.interest_fee) benxi,d.total,m.user_name,bi.investor_capital,bi.add_time')->where("d.borrow_id={$iinfo['borrow_id']} and d.invest_id ={$iinfo['id']}")->group('d.investor_uid')->find();
		
		$time = M('transfer_borrow_investor')->field('id,add_time')->where("borrow_id={$iinfo['borrow_id']} order by add_time asc")->limit(1)->find();
		
		$deadline_last = strtotime("+{$binfo['borrow_duration']} month",$time['add_time']);
		
		$this->assign('deadline_last',$deadline_last);
		$this->assign('detailinfo',$detailinfo);

		$type1 = $this->gloconf['BORROW_USE'];
		$binfo['borrow_use'] = $type1[$binfo['borrow_use']];



		$type = $borrow_config['REPAYMENT_TYPE'];
		//echo $binfo['repayment_type'];
		$binfo['repayment_name'] = $type[$binfo['repayment_type']];

		$iinfo['repay'] = getFloatValue(($iinfo['investor_capital']+$iinfo['investor_interest'])/$binfo['borrow_duration'],2);
		$this->assign('iinfo',$iinfo);
		$this->assign('binfo',$binfo);
		$this->assign('mBorrow',$mBorrow);
		$this->assign('mInvest',$mInvest);

		$detail_list = M('transfer_investor_detail')->field(true)->where("invest_id={$invest_id}")->select();
		$this->assign("detail_list",$detail_list);

		$ht=M('hetong')->field('hetong_img,name,dizhi,tel')->find();
		$this->assign("ht",$ht);
		$this->display("transfer");
    }
}