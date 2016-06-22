<?php
    class InvestAction extends HCommonAction
    {
		public function index()
        {
			
            $maprow = array();
            $searchMap['borrow_status']=array("in",'2,4,6,7'); 
			$searchMap['repayment_type']=array("neq",'1');
            $parm['map'] = $searchMap;
            $parm['pagesize'] = 5;
            $sort = "desc";
            $parm['orderby']="b.borrow_status ASC,b.id DESC";
            $list = getBorrowList($parm);
            $Bconfig = require C("APP_ROOT")."Conf/borrow_config.php"; 
            if($this->isAjax()){
                $string ='';
                foreach($list['list'] as $vb){
				
                    
					$string .= '
                        <table >
				<tr>
					<td colspan="2" class="title1">
                            <a href="'.U('m/invest/detail', array('id'=>$vb['id'])).'" >JK'.$vb['id'].cnsubstr($vb['borrow_name'],17).'</a>
                    </td>
					<td colspan="2" class="title1" >
					    
						<canvas class="proces" width=40 height=40 value="'.$vb['progress'].'"></canvas>
					</td>
				</tr>
				<tr>
					<th>配资金额:</th><td class="amount">'.getMoneyFormt($vb['borrow_money']).'</td>
					<th>还款期限:</th><td>'.$vb['borrow_duration']; 
                              $string .= $vb['repayment_type']==1?'天':'个月';    
                              
                                $string .='</td>		
				</tr>
				<tr>
					<th>年　利率:</th>
					<td>10.8%</td>
					<th>还需资金:</th>
					<td class="amount">'.getMoneyFormt($vb['need']).'</td>		
				</tr>
				<tr>
					<td colspan="4" class="foo">'.borrow_status($vb['id'], $vb['borrow_status']).'</td>	
				</tr>
			</table>';
						
                                
                }
                echo $string;
            }else{
                
                ///////////////企业直投列表开始 /////////////
                $parm = array();
                $Map  = ' b.borrow_status = 2 and b.is_show=1 and b.transfer_total > b.transfer_out';
                $parm['map'] = $Map;
                $parm['orderby'] = "b.is_show desc,b.id DESC";
                $listTBorrow = getTBorrowList($parm);
                $this->assign("listTBorrow",$listTBorrow);
                ///////////////企业直投列表结束 /////////////
        
                $this->assign('list', $list);
                $this->assign('Bconfig', $Bconfig);
                $this->display(); 
            }
        }
        public function detail()
        {   
            
            $pre = C('DB_PREFIX');
            $id = intval($_GET['id']);
            
            $Bconfig = require C("APP_ROOT")."Conf/borrow_config.php";

            $borrowinfo = M("borrow_info bi")
                            ->field('bi.*,ac.title,ac.id as aid')
                            ->join('lzh_article ac on ac.id= bi.danbao')
                            ->where('bi.id='.$id)
                            ->find();
            
            if(!$borrowinfo || ($borrowinfo['borrow_status']==0 && $this->uid!=$borrowinfo['borrow_uid']) )
                $this->error("数据有误");
            $borrowinfo['biao'] = $borrowinfo['borrow_times'];
            $borrowinfo['need'] = $borrowinfo['borrow_money'] - $borrowinfo['has_borrow'];
            $borrowinfo['lefttime'] =$borrowinfo['collect_time'] - time();
            $borrowinfo['progress'] = getFloatValue($borrowinfo['has_borrow']/$borrowinfo['borrow_money']*100,2);
            $this->assign("vo",$borrowinfo);  
            //borrowinfo
       
            //此标借款利息还款相关情况
            //memberinfo
            $memberinfo = M("members")
                            ->field("id,customer_name,customer_id,user_name,reg_time,credits")
                            ->where("id={$borrowinfo['borrow_uid']}")
                            ->find();
            $this->assign("minfo",$memberinfo);
            //memberinfo
            $this->assign("Bconfig",$Bconfig);
            $this->assign("gloconf",$this->gloconf);
            $this->display();
        }
        
        /**
        * 手机普通标投资
        */
        public function Invest()
        {   
            if(!$this->uid){
                if($this->isAjax()){
                    die("请先登录后投资");   
                }else{
                    $this->redirect('M/pub/login'); 
                    
                }
            }
            if($this->isAjax()){   // ajax提交投资信息
         
                $borrow_id = intval($this->_get('bid'));
                $invest_money = intval($this->_post('invest_money'));
                $paypass = $this->_post('paypass');
                $invest_pass = isset($_POST['invest_pass'])?$_POST['invest_pass']:'';

                $status = checkInvest($this->uid, $borrow_id, $invest_money, $paypass, $invest_pass);
                if($status == 'TRUE'){
                    $done = investMoney($this->uid,$borrow_id,$invest_money);
                    if($done === true){
                        die('TRUE');
                    }
                    elseif($done){
                        die($done);
                    }else{
                        die(L('investment_failure'));
                    }   
                }else{
                    die($status);   
                }
            }else{  
                $borrow_id = $this->_get('bid');
                $borrow_info = M("borrow_info")
                    ->field('borrow_duration, borrow_money, borrow_interest, borrow_interest_rate, has_borrow,
                             borrow_min, borrow_max, password, repayment_type')
                    ->where("id='{$borrow_id}'")
                    ->find();
                $this->assign('borrow_info', $borrow_info);    
                
                $user_info = M('member_money')
                                ->field("account_money+back_money as money ")
                                ->where("uid='{$this->uid}'")
                                ->find();
                $this->assign('user_info', $user_info);
                $paypass = M("members")->field('pin_pass')->where('id='.$this->uid)->find();
                $this->assign('paypass', $paypass['pin_pass']);
                $this->display();   
            }
        }
    }
?>
