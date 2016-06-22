<?php
    class BorrowAction extends HCommonAction{
		
        public function index(){   
            $this->display();
        }
        
        /**
        * 手机普通标投资
        */
        public function month(){
            
           
			if(!$this->uid) 
		        $this->error("请先登录",__APP__."/M/pub/login");
		         
			
			//判断是否实名认证
			$ids = M('members_status')->getFieldByUid($this->uid,'id_status');
			if($ids!=1){
			    $this->error("请先进行实名认证并确认通过后，再发标",__APP__."/M/User/index");
			}
			//判断是否手机认证
			$mo =  M('members_status')->getFieldByUid($this->uid,'phone_status');
			if($mo['phone_status']!=1)
			    $this->error("请先进行手机认证");
			
			//判断是否有借款中的标
			$_xoc = M('borrow_info')->where("borrow_uid={$this->uid} AND borrow_status in(0,2,4)")->count('id');
			if($_xoc>0)  $this->error("您有一个借款中的标，请等待审核",__APP__."/M/user/loan");
			    
			$per = C('DB_PREFIX');
			
			
			$cordon=$this->glo['borrow_cordon'];
			$unwind=$this->glo['borrow_unwind'];
			$money_deposit=M('global')->field('text')->where("id=74")->find();
			$this->assign('cordon', $cordon);
			$this->assign('unwind', $unwind);
			$this->assign('money_deposit', $money_deposit);
			$fee_borrow_manage=M('global')->field('text')->where("id=79")->find();
			$fee_borrow_manage=explode("|",$fee_borrow_manage['text']);
			$this->assign('fee_borrow_manage', $fee_borrow_manage);
			
			$gtype = text($_GET['type']);
			$vkey = md5(time().$gtype);
			switch($gtype){
				case "normal"://普通标
					$borrow_type=1;
				break;
				case "vouch"://新担保标
					$borrow_type=2;
				break;
				case "second"://秒还标
					$this->assign("miao",'yes');
					$borrow_type=3;
				break;
				case "net"://净值标
					$borrow_type=4;
				break;
				case "mortgage"://抵押标
					$borrow_type=5;
				break;
			}
		
			cookie($vkey,$borrow_type,3600);
			$this->assign("vkey",$vkey);
			$str=M('global')->field('text')->where("id=66")->find();
			$borrow_interest_rate=explode("|",$str['text']); 
			$this->assign('borrow_interest_rate', $borrow_interest_rate['1']);
			
			$this->display();
		
        }
        
		public function day(){   
			if(!$this->uid) 
			    $this->error("请先登录",__APP__."/M/pub/login");
			//判断是否实名认证
			$ids = M('members_status')->getFieldByUid($this->uid,'id_status');
			if($ids!=1){
			    $this->error("请先进行实名认证并确认通过后，再发标");
			}
			//判断是否手机认证
			$mo =  M('members_status')->getFieldByUid($this->uid,'phone_status');
			if($mo['phone_status']!=1)
			    $this->error("请先进行手机认证");
				
			//判断是否有借款中的标
			$_xoc = M('borrow_info')->where("borrow_uid={$this->uid} AND borrow_status in(0,2,4)")->count('id');
			if($_xoc>0)  $this->error("您有一个借款中的标，请等待审核",__APP__."/M/user/loan");
			
			
			$per = C('DB_PREFIX');
			
			$cordon=$this->glo['borrow_cordon'];
			$unwind=$this->glo['borrow_unwind'];
			$money_deposit=M('global')->field('text')->where("id=74")->find();
			$this->assign('cordon', $cordon);
			$this->assign('unwind', $unwind);
			$this->assign('money_deposit', $money_deposit);
			
			$fee_borrow_manage=M('global')->field('text')->where("id=79")->find();
			$fee_borrow_manage=explode("|",$fee_borrow_manage['text']);
			$this->assign('fee_borrow_manage', $fee_borrow_manage);
			
			$gtype = text($_GET['type']);
			$vkey = md5(time().$gtype);
			switch($gtype){
				case "normal"://普通标
					$borrow_type=1;
				break;
				case "vouch"://新担保标
					$borrow_type=2;
				break;
				case "second"://秒还标
					$this->assign("miao",'yes');
					$borrow_type=3;
				break;
				case "net"://净值标
					$borrow_type=4;
				break;
				case "mortgage"://抵押标
					$borrow_type=5;
				break;
			}
		
			cookie($vkey,$borrow_type,3600);
			$this->assign("vkey",$vkey);
			$str=M('global')->field('text')->where("id=66")->find();
			$borrow_interest_rate=explode("|",$str['text']); 
			$this->assign('borrow_interest_rate', $borrow_interest_rate['1']);
			
			$this->display();
		
        }
		
		//提交借款
		public function save_month(){
			if(!$this->uid) 
			    $this->error("请先登录",__APP__."/M/pub/login");
			$_xoc = M('borrow_info')->where("borrow_uid={$this->uid} AND borrow_status in(0,2,4)")->count('id');
			if($_xoc>0)  $this->error("您有一个借款中的标，请等待审核",__APP__."/M/user/index");
			$pre = C('DB_PREFIX');
			//得到借款金额和期限
			$borrow['borrow_deposit'] = intval($_POST['borrow_deposit']);
			if($borrow['borrow_deposit']<5000) $this->error("配资本金必须不能小于5000元！");
			if($borrow['borrow_deposit']=="") $this->error("配资本金不能为空！");
			if($borrow['borrow_deposit']%100!=0) $this->error("配资本金必须是100的整数倍！");
			if($borrow['borrow_deposit']>500000) $this->error("配资本金不能大于50万元！");
			$borrow['borrow_duration'] = intval($_POST['borrow_duration']);
			if ($borrow['borrow_duration']=="") $this->error("配资期限不得为空！");
			if ($borrow['borrow_duration']>6) $this->error("配资期限不得大于6个月！");
			$money_deposit = $this->glo['money_deposit'];
			$borrow['borrow_money']=$borrow['borrow_deposit']*$money_deposit;
			//相关的判断参数，利息、期限、管理费、保证金
			$rate_lixt = explode("|",$this->glo['rate_lixi']);
			$borrow_duration = explode("|",$this->glo['borrow_duration']);
			$fee_borrow_manage = explode("|",$this->glo['fee_borrow_manage']);
			
			$vminfo = M('members m')->join("{$pre}member_info mf ON m.id=mf.uid")->field("m.user_leve,m.time_limit,mf.province_now,mf.city_now,mf.area_now")->where("m.id={$this->uid}")->find();
			$borrow['borrow_type'] = intval(cookie($_POST['vkey']));
			//
			$_minfo = getMinfo($this->uid,"m.pin_pass,mm.account_money,mm.back_money,mm.credit_cuse,mm.money_collect");
			$_capitalinfo = getMemberBorrowScan($this->uid);
			
			$borrow['borrow_uid'] = $this->uid;
			$borrow['borrow_name'] = "股票抵押借款";
			$borrow['borrow_interest_rate'] =$rate_lixt[1];
			$borrow['total'] = 1;
			$borrow['add_time'] = time();
			$borrow['add_ip'] = get_client_ip();
			$borrow['repayment_type']=5;
			$borrow['borrow_interest'] = getBorrowInterest($borrow['repayment_type'],$borrow['borrow_money'],$borrow['borrow_duration'],$borrow['borrow_interest_rate']);
			$borrow['borrow_status'] = 0;
			$borrow['collect_day'] = 7;
			
			if($borrow['borrow_duration']>=1&&$borrow['borrow_duration']<=$fee_borrow_manage[3])
				$borrow_fee_rate=$fee_borrow_manage[1]/100;
			else if ($borrow['borrow_duration']>$fee_borrow_manage[3]&& $borrow['borrow_duration']<=$borrow_duration[1])
				$borrow_fee_rate=$fee_borrow_manage[2]/100;
			$borrow['borrow_fee']=$borrow['borrow_money']*$borrow_fee_rate*$borrow['borrow_duration'] ;
			
			$accout_num=intval($_minfo['account_money']);
			$borrow_cost=intval($borrow['borrow_deposit']+$borrow['borrow_interest']+$borrow['borrow_fee']);
			if($accout_num<$borrow_cost) $this->error("您的可用余额为{$accout_num}元，小于您借款额".$borrow['borrow_money']."所需的保证金要求".$borrow_cost."，请先充值",__APP__."/member/charge#fragment-1 ");
			
			foreach($_POST['swfimglist'] as $key=>$v){
    			if($key>10) break;
    			$row[$key]['img'] = substr($v,1);
    			$row[$key]['info'] = $_POST['picinfo'][$key];
		    }
    		$borrow['updata']=serialize($row);
    		$newid = M("borrow_info")->add($borrow);
    		
    		$suo=array();
    		$suo['id']=$newid; 
            $suo['suo']=0;
            $suoid = M("borrow_info_lock")->add($suo);
    		
    		if($newid) 
    			$this->success("借款发布成功，网站会尽快初审",__APP__."/M/user/index");
    		else $this->error("发布失败，请先检查借款信息是否正确，然后重试");
			
		}
		
		
		public function save_day(){
			
			$pre = C('DB_PREFIX');
			//得到借款金额和期限
			$borrow['borrow_money'] = intval($_POST['borrow_money']);
			if($borrow['borrow_money']<10000) $this->error("配资金额必须大于10000元！");
			if($borrow['borrow_money']=="") $this->error("配资金额不能为空！");
			if($borrow['borrow_money']%300!=0) $this->error("配资金额必须是最小投资金额300整数倍！");
			if($borrow['borrow_money']>1500000) $this->error("配资金额不能大于1500000元！");
			$borrow['borrow_duration'] = intval($_POST['borrow_duration']);
			if ($borrow['borrow_duration']=="") $this->error("配资期限不得为空！");
			if ($borrow['borrow_duration']>30) $this->error("配资期限不得大于30天！");
			//相关的判断参数，利息、期限、管理费、保证金
			$rate_lixt = explode("|",$this->glo['rate_lixi']);
			$borrow_duration = explode("|",$this->glo['borrow_duration_day']);
			$fee_borrow_manage = explode("|",$this->glo['fee_borrow_manage']);
			$money_deposit = $this->glo['money_deposit'];
			$vminfo = M('members m')->join("{$pre}member_info mf ON m.id=mf.uid")->field("m.user_leve,m.time_limit,mf.province_now,mf.city_now,mf.area_now")->where("m.id={$this->uid}")->find();
			$borrow['borrow_type'] = intval(cookie($_POST['vkey']));
			//
			$_minfo = getMinfo($this->uid,"m.pin_pass,mm.account_money,mm.back_money,mm.credit_cuse,mm.money_collect");
			$_capitalinfo = getMemberBorrowScan($this->uid);
			
			$borrow['borrow_uid'] = $this->uid;
			$borrow['borrow_name'] = "股票抵押借款";
			$borrow['borrow_interest_rate'] =$rate_lixt[0];
			$borrow['total'] = 1;
			$borrow['add_time'] = time();
			$borrow['add_ip'] = get_client_ip();
			$borrow['repayment_type']=1;
			$borrow['borrow_interest'] = getBorrowInterest($borrow['repayment_type'],$borrow['borrow_money'],$borrow['borrow_duration'],$borrow['borrow_interest_rate']);
			$borrow['borrow_status'] = 0;
			$borrow['collect_day'] = 7;
			$borrow['borrow_deposit']=$borrow['borrow_money']/$money_deposit;
			$borrow_fee_rate=$fee_borrow_manage[0]/100;
			$borrow['borrow_fee']=$borrow['borrow_money']*$borrow_fee_rate*$borrow['borrow_duration'] ;
			
			foreach($_POST['swfimglist'] as $key=>$v){
			if($key>10) break;
			$row[$key]['img'] = substr($v,1);
			$row[$key]['info'] = $_POST['picinfo'][$key];
    		}
    		$borrow['updata']=serialize($row);
    		$newid = M("borrow_info")->add($borrow);
    
    		$suo=array();
    		$suo['id']=$newid; 
            $suo['suo']=0;
            $suoid = M("borrow_info_lock")->add($suo);
    		
    		if($newid) 
    		    $this->success("借款发布成功，网站会尽快初审",__APP__."/M/user/index");
    		else
    		     $this->error("发布失败，请先检查借款信息是否正确，然后重试");
    			
    		}
		
       }
?>