<?php
    class BillinvestAction extends HCommonAction
    {
		public function index()
        {	
            $maprow = array();
            $searchMap['status']=array("in",'2,4,7,8'); 
            $parm['map'] = $searchMap;
            $parm['pagesize'] =5;
            $sort = "desc";
            $parm['orderby']="b.status ASC,b.id DESC";
			
            $list = getBillList($parm);
			
            $Bconfig = require C("APP_ROOT")."Conf/bill_config.php"; 
            if($this->isAjax()){
                $string ='';
                foreach($list['list'] as $vb){     
					$string .= '
                        <table >
				<tr>
					<td colspan="2" class="title1">
                            <a href="'.U('m/billinvest/detail', array('id'=>$vb['id'])).'" >JK'.$vb['id'].cnsubstr($vb['name'],17).'</a>
                    </td>
					<td colspan="2" class="title1" >
					    
						<canvas class="proces" width=40 height=40 value="'.$vb['progress'].'"></canvas>
					</td>
				</tr>
				<tr>
					<th>配资金额:</th><td class="amount">'.getMoneyFormt($vb['amount']).'</td>
					<th>还款期限:</th><td>'.date('Y年m月d日',$vb['deadline']).'</td>		
				</tr>
				<tr>
					<th>年　利率:</th>
					<td>'.$vb['interest_rate'].'</td>
					<th>还需资金:</th>
					<td class="amount">'.getMoneyFormt($vb['need']).'</td>		
				</tr>
				<tr>
					<td colspan="4" class="foo">'.bill_status($vb['id'],$vb['status']).'</td>	
				</tr>
			    </table>';
						
                                
                }
                echo $string;
            }else{
                $this->assign('list', $list);
                $this->assign('Bconfig', $Bconfig);
                $this->display(); 
            }
        }
		
        public function detail(){   
            
            $pre = C('DB_PREFIX');
            $id = intval($_GET['id']);
            $Bconfig = require C("APP_ROOT")."Conf/bill_config.php";
            $bill = M("bill")->where('id='.$id)->find();
            $bill['lefttime'] = $bill['collect_time']- time();
			$bill['need'] = $bill['amount'] - $bill['has_borrow'];
			$bill['invest_duration'] = (ceil(($bill['deadline']-time())/86400)>0)?(ceil(($bill['deadline']-time())/86400)):0;
            $memberinfo = M("members")
                            ->field("id,customer_name,customer_id,user_name,reg_time,credits")
                            ->where("id={$bill['uid']}")
                            ->find();
			
			$parm['map']="b.bill_id={$id}";
			$parm['orderby']="b.invest_time DESC";
			$list = getRecordList($parm);
			$this->assign("list",$list);
			
			
			
			$this->assign("vo", $bill); 
            $this->assign("minfo",$memberinfo);
            $this->assign("Bconfig",$Bconfig);
            $this->assign("gloconf",$this->gloconf);
            $this->display();
        }
        
        /**
        * 手机普通标投资
        */
        public function Invest(){   
            if (!$this->uid)   $this->error("请先登录",__APP__."/M/pub/login");
			$bill_id = $this->_get('id');
			$bill = M("bill")
				->where("id='{$bill_id}'")
				->find();
			if ($this->uid == $bill['uid']) $this->error("不能对自己的融资项目进行投资！");
			$bill['need'] = $bill['amount'] - $bill['has_borrow'];
			$this->assign('vo', $bill);    
			
			$user_info = M('member_money')
							->field("account_money+back_money as money ")
							->where("uid='{$this->uid}'")
							->find();
			$this->assign('user_info', $user_info);
			$paypass = M("members")->field('pin_pass')->where('id='.$this->uid)->find();
			$this->assign('paypass', $paypass['pin_pass']);
			$this->display();   
            
        }
		public function save (){
			$uid = $this->uid;
			$bill_id = text($_POST['id']);
			$paypass = md5($_POST['paypass']);
			$data['invest_amount'] = text($_POST['invest_money']);
			
			//查询 bill member_money members 表
			$bill = M('bill')->where("id = {$bill_id}")->find();
			$m_money = M('member_money')->field("account_money,money_collect")->where("uid = {$uid}")->find();
			$members = M('members')->field("pin_pass")->where("id = {$uid}")->find();
			
			//判断支付密码
			if ($members['pin_pass'] != $paypass) $this->error("支付密码错误！");
			
			$bill['has_borrow'] = $bill['has_borrow'] + $data['invest_amount'] ;
			if ($bill['has_borrow'] > $bill['amount']) $this->error("您所投金额已超出融资上限，请修改投资金额！");
			if ($bill['has_borrow'] == $bill['amount']) $bill['status'] = 4;
			//投资金额操作
			if ($data['invest_amount'] > $m_money['account_money']) $this->error(" 您的余额小于投资金额，请尽快充值！",__APP__."/M/user/index");
			$freeze = memberMoneyLog($uid,6,-$data['invest_amount'],"对{$bill_id}号标进行投标，冻结金额{$data['invest_amount']}元！");
			if(!$freeze) $this->error("投标失败");
			//保存has_borrow
			$newid = M('bill')->save($bill);
			if (!$newid) $this->error("投标失败");
			//保存投资表
			$data['bill_id'] = $bill_id;
			$data['invest_uid'] = $uid;
			$data['invest_time'] = time();
			$data['invest_duration'] = ceil(($bill['deadline']-$data['invest_time'])/86400);
			$data['invest_interest'] = $data['invest_amount'] * $bill['interest_rate']/100 * $data['invest_duration']/360;
			$newid1 = M('bill_invest')->add($data);
			if ($newid1) {
				$this->success("投标成功",__APP__."/M/billinvest/index");
			}else {
				$this->error("投标失败");
			}
		}
    }
?>
