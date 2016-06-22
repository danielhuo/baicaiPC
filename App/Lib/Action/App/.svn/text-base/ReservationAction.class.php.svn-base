<?php
    class ReservationAction extends HCommonAction
    {
		public function index()
        {	
			$parm['orderby'] = "r.id DESC";
			$parm['pagesize'] = 5;
			$parm['limit'] = 5;			
			$list = getReservationList($parm);
			
			
			//dump($list);
			//   $Bconfig = require C("APP_ROOT")."Conf/bill_config.php"; 
            if($this->isAjax()){
                $string ='';
                foreach($list['list'] as $vb){     
					$string .= '
                        <table >
				<tr>
					<td colspan="2" class="title1">
                            <a href="'.U('m/reservation/detail', array('id'=>$vb['id'])).'" >'.$vb['name'].'</a>
                    </td>
					<td colspan="2" class="title1" >
					    
						
					</td>
				</tr>
				<tr>
					<th>配资金额:</th><td class="amount">'.getMoneyFormt($vb['amount']).'</td>
					<th>还款期限:</th><td>'.$vb['duration'].'天</td>		
				</tr>
				<tr>
					<th>年　利率:</th>
					<td>'.$vb['interest_rate'].'%</td>
					<th>还需资金:</th>
					<td class="amount">'.getMoneyFormt($vb['need']).'</td>		
				</tr>
				<tr>
					<td colspan="4" class="foo"><a href="'.U('m/reservation/detail', array('id'=>$vb['id'])).'" class="tz_bt">预约详情</a></td>	
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
            $rs = M("reservation_project")->where('id='.$id)->find();
			$rs['need'] =$rs['amount'] -$rs['has_borrow'];
   
           $list = M("reservation_invest r")->field("m.user_name,r.invest_amount")->join("{$pre}members m ON r.invest_uid = m.id")->where("r.r_id={$id}")->select();
		
			
			
			$this->assign("vo", $rs); 
            $this->assign("list",$list);
            $this->display();
        }
        
        /**
        * 手机普通标投资
        */
        public function reserve(){   
            $this->display();
            
        }
		
		
		public function save(){
			$data["telephone"]=$_POST["telephone"];
			$data["p_id"]=$_POST["id"];
			$data['bill_amount']=$_POST["price"];
			$data['access_name']=$_POST["name"];
			$data['type']=$_POST["type"];
			
		
			$regExp="/^[1][3-8]+\\d{9}/";
			if($data["telephone"]==""||$data["bill_amount"]==""||$data["access_name"]==""){
				ajaxmsg("请将数据填写完整",1); 
				exit;
				
			}
			if(!preg_match($regExp,$data["telephone"])){
				ajaxmsg("号码格式出错",2);  
				exit; 
			}
			if(!is_numeric($data['bill_amount'])){
				ajaxmsg("请输入数字",3);
			}
			
			$data["status"]=0;
			$data["access_time"]=time();
			$uid=M("accesstel")->add($data);
			if($uid>0){
				ajaxmsg($data["access_time"],4);
			}	
			ajaxmsg($data["access_time"],5);
		//$this->success("提交号码成功","__URL__/index");
			
		
	}
		
    }
?>
