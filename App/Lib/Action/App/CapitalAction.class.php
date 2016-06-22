<?php

  class CapitalAction extends AppAction{
  		 var $needlogin=true;
  		 /**
         * 资金信息
         */
         public function index()
         {	
         	$pre = C('DB_PREFIX');		 
			$repay =M("loan l")->where("loan_uid={$this->uid} and status=7")->sum("loan_amount");
			$return= M("loan l")->join("{$pre}loan_invest i ON i.loan_id = l.id")
			->where("i.invest_uid ={$this->uid} and l.status=7")
			->sum("i.invest_amount");
             $minfo =getMinfo($this->uid,true);
             $fund['repay']=$repay?Fmoney($repay):0;
            $fund['return']=$return?Fmoney($return):0;
             $fund['account']=$minfo['account_money']?$minfo['account_money']:0;
             $fund['freeze']=$minfo['money_freeze']?$minfo['money_freeze']:0;
             ajaxmsg($fund,0);
         }
		  

  		/**
         * 提现页面
         */
         public function cash()
         {
             
             $pre = C('DB_PREFIX');
             $field = "(mm.account_money+mm.back_money) account_money,b.bank_num,b.bank_name";
             $vo = M('members m')->field($field)->join("{$pre}member_info i on i.uid = m.id")->join("{$pre}member_money mm on mm.uid = m.id")->join("{$pre}member_banks b on b.uid = m.id")->where("m.id={$this->uid}")->find();
            
             if(empty($vo['bank_num'])) 
                ajaxmsg("请用电脑登录先绑定银行卡后申请提现！",1);
             $config = FS("App/Conf/config");   
             $vo['bank_img']=$config['bank_img'][$vo['bank_name']];
             $vo['bank_num']=hidecard($vo['bank_num'],3);
             
             $data['account']=$vo;
             ajaxmsg($data,0);
             
         }
         /*
         *提现操作
         */
         public function docash(){
            $money = $_POST['money'];
            $paypass = $_POST['paypass'];
            $status = checkCash($this->uid, $money, $paypass);
            if($status == 'TRUE'){
              ajaxmsg("提现申请提交成功",0);
            }else{
              ajaxmsg($status,1);   
            }


         }

         /**
         * 资金记录
         */
         public function  records()
         {
            
            $map['uid'] = $this->uid;
			$parm['map'] =$map;
			
            $parm['pagesize'] =20;
           $list = getMoneyLog($parm);
			
        //   $list = getMoneyLog($map,15);
	        
			ajaxmsg($list,0);
		
			
			
         }
	  
	   public function repaylist(){
	    	$np=$_REQUEST['p']?$_REQUEST['p']:1;
		    import("ORG.Util.Page");
			$count =  M("loan_repay r")
				->join("{$this->pre}loan l ON r.lid = l.id")
				->field("r.lid,l.loan_name,l.pay_periods,r.deadline,r.num_period,r.repay_amount,r.status,r.deadline")
				->where("l.loan_uid={$this->uid}")
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
				->where("l.loan_uid={$this->uid}")
				->select();
			foreach ($repaylist as $key => $value) {
				$repaylist[$key]["repay_amount"]=number_format($value['repay_amount'],2);
				$repaylist[$key]["deadline"]=date("Y/m/d",$value['deadline']);
			}
			$pager['nowPage']=$np;
		    $pager['total']=ceil($count/30);
			$data['list']=$repaylist;
			$data['page']=$pager;
			ajaxmsg($data,0);
			
	}
	  
	public function returnlist(){
		$np=$_REQUEST['p']?$_REQUEST['p']:1;
		import("ORG.Util.Page");	
		$count = M("loan_invest_return r")
			->join("{$this->pre}loan_repay p ON p.id=r.rid")
			->join("{$this->pre}loan l ON p.lid=l.id")
			->join("{$this->pre}loan_invest i ON i.id=r.iid")
			->field("p.lid,p.deadline,l.loan_name,l.pay_periods,p.num_period,r.return_amount,p.status")
			->where("i.invest_uid = {$this->uid}")
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
			->where("i.invest_uid = {$this->uid}")
			->select();
		foreach ($returnlist as $key => $value) {
			$returnlist[$key]["return_amount"]=number_format($value['return_amount'],2);
			$returnlist[$key]["deadline"]=date("Y/m/d",$value['deadline']);
		}
		$pager['nowPage']=$np;
		$pager['total']=ceil($count/30);
		$data['list']=$returnlist;
		$data['page']=$pager;
		ajaxmsg($data,0);
		
	}
	  
  }
?>