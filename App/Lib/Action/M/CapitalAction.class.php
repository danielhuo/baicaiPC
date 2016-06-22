<?php

  class CapitalAction extends MobileAction{
	  
	  /**
         * 资金信息
         */
         public function index()
         {	
			 $pre = C('DB_PREFIX');
			 // $repay_amount = M("loan_repay r")->join("{$pre}loan l ON r.lid = l.id")->where("l.loan_uid={$this->uid} and r.status = 0")->sum('r.repay_amount');
		  //    $return_amount =M("loan_invest_return r")->join("{$pre}loan_repay p ON p.id=r.rid")->join("{$pre}loan_invest i ON i.id=r.iid")->where("i.invest_uid = {$this->uid} and p.status=0")->sum("r.return_amount");

			$repay_amount =M("loan l")->where("loan_uid={$this->uid} and status=7")->sum("loan_amount");
			$return_amount= M("loan l")->join("{$pre}loan_invest i ON i.loan_id = l.id")
			->where("i.invest_uid ={$this->uid} and l.status=7")
			->sum("i.invest_amount");

		     $this->assign("repay_amount",$repay_amount);
		     $this->assign("return_amount",$return_amount);
             $this->assign('pcount', get_personal_count($this->uid));   
             $this->assign('benefit', get_personal_benefit($this->uid));   //收入
             $minfo =getMinfo($this->uid,true);
             $this->assign("minfo",$minfo); 
             $this->display();
         }
		 
		 
		
		 
         
	   public function repaylist(){
		   import("ORG.Util.Page");
		$id = $this->uid;
		$count =  M("loan_repay r")
			->join("{$this->pre}loan l ON r.lid = l.id")
			->field("r.lid,l.loan_name,l.pay_periods,r.deadline,r.num_period,r.repay_amount,r.status,r.deadline")
			->where("l.loan_uid={$id}")
			->count('r.id');
		$p = new Page($count,30);
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		$order= "r.status ,r.lid,r.deadline";
		
		$repaylist = M("loan_repay r")
			->join("{$this->pre}loan l ON r.lid = l.id")
			->field("l.loan_name,l.pay_periods,r.deadline,r.lid,r.num_period,r.repay_amount,r.status")
			->order($order)
			->limit($Lsql)
			->where("l.loan_uid={$id}")
			->select();
		
		
		$this->assign("repaylist",$repaylist);	
		$this->assign("pagebar", $page);
		$this->display();
		
	}
	
	public function returnlist(){
		import("ORG.Util.Page");
		$id = $this->uid;
		$count = M("loan_invest_return r")
			->join("{$this->pre}loan_repay p ON p.id=r.rid")
			->join("{$this->pre}loan l ON p.lid=l.id")
			->join("{$this->pre}loan_invest i ON i.id=r.iid")
			->field("p.lid,p.deadline,l.loan_name,l.pay_periods,p.num_period,r.return_amount,p.status")
			->where("i.invest_uid = {$id}")
			->count('r.id');
		$p = new Page($count,30);
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		$order= "p.status ,p.lid ,p.deadline";
		$returnlist = M("loan_invest_return r")
			->join("{$this->pre}loan_repay p ON p.id=r.rid")
			->join("{$this->pre}loan l ON p.lid=l.id")
			->join("{$this->pre}loan_invest i ON i.id=r.iid")
			->field("p.lid,l.loan_name,l.pay_periods,p.num_period,p.deadline,r.return_amount,p.status")
			->order($order)
			->limit($Lsql)
			->where("i.invest_uid = {$id}")
			->select();
		//dump($returnlist);
		$this->assign("returnlist",$returnlist);
			$this->assign("pagebar", $page);
		$this->display();
		
	}

	
         /**
         * 资金记录
         */
         public function  records()
         {
            $logtype = C('MONEY_LOG');
            $this->assign('log_type',$logtype);

            $map['uid'] = $this->uid;
			$parm['map'] =$map;
			
            $parm['pagesize'] =20;
           $list = getMoneyLog($parm);
			
        //   $list = getMoneyLog($map,15);
	        if($this->isAjax()){
				echo json_encode($list['list']);
		
			
			}else{

				$this->assign("list",$list['list']);        
				$this->assign("pagebar",$list['pager']);    
				$this->display();
			}
         }
         

	/**
         * 我要提现
         */
         public function cash()
         {
             if($this->isAjax()){
                  $money = $this->_post('money');
                  $paypass = $this->_post('paypass');
                  $status = checkCash($this->uid, $money, $paypass);
                  if($status == 'TRUE'){
                      ajaxmsg("提交成功",0);
                  }else{
                      ajaxmsg($status,1);
                  }
             }else{
                 $pre = C('DB_PREFIX');
                 $field = "m.user_name,m.user_phone,(mm.account_money+mm.back_money) all_money,mm.account_money,mm.back_money,i.real_name,b.bank_num,b.bank_name,b.bank_address";
                 $vo = M('members m')->field($field)->join("{$pre}member_info i on i.uid = m.id")->join("{$pre}member_money mm on mm.uid = m.id")->join("{$pre}member_banks b on b.uid = m.id")->where("m.id={$this->uid}")->find();
                 //print_r($vo);exit;
                 if(empty($vo['bank_num'])) 
                    $this->error("请用电脑登录先绑定银行卡后申请提现！");
                 $config = FS("App/Conf/config");   
                 $vo['bank_img']=$config['bank_img'][$vo['bank_name']];
                 $tqfee = explode( "|", $this->glo['fee_tqtx']);
                 $fee[0] = explode( "-", $tqfee[0]);
                 $fee[1] = explode( "-", $tqfee[1]);
                 $fee[2] = explode( "-", $tqfee[2]);
                 $this->assign( "fee",$fee);
                 $borrow_info = M("borrow_info")
                            ->field("sum(borrow_money+borrow_interest+borrow_fee) as borrow, sum(repayment_money+repayment_interest) as also")
                            ->where("borrow_uid = {$this->uid} and borrow_type=4 and borrow_status in (0,2,4,6,8,9,10)")
                            ->find();
                 $vo['all_money'] -= $borrow_info['borrow'] + $borrow_info['also'];
                 $this->assign("borrow_info", $borrow_info);
                 $this->assign( "vo",$vo);
                 $this->assign("memberinfo", M('members')->find($this->uid));
                 $this->display(); 
             }  
         }
	  
  }
?>