<?php
    class AdminAction extends MobileAction
    {	
		var $notneedlogin=true;
        public function index(){
            $this->display();   
        }
        public function total(){
			$map['m.id'] = array("not in","106");
			$member = M("members m")->where($map)->count();
			
			$amount = M("members m")
			->join("{$this->pre}member_money  n on m.id = n.uid")
			->where($map)
			->sum('account_money');
			
			$has_invest =M('loan_invest')->count('DISTINCT invest_uid');
			$often_invest =M('loan_invest')->query('select count(*) as b from (select  count(*) as total from lzh_loan_invest 
			group by invest_uid having total>2) aa');
			
			
			$investable =M('member_money')->where("account_money>0")->count("uid");
		
			$this->assign(member,$member);
			$this->assign(amount,$amount);
			$this->assign("has_invest",$has_invest);
			$this->assign("often_invest",$often_invest[0]['b']);
			$this->assign("investable",$investable);
			
            $this->display();   
        }

		public function promotion_list(){
			$period = intval($_POST['period'])?intval($_POST['period']):1;
			$type = intval($_POST['type'])?intval($_POST['type']):0;
			//时间
			switch ($period){
				case 1:
					$start_time = 1429757860;
					$end_time = time();
					break; 
				case 2:
					$start_time = mktime(0,0,0,date('m'),1,date('Y'));
					$end_time = time();
					break;
				case 3;
					$start_time = strtotime('-1 month',mktime(0,0,0,date('m'),1,date('Y')));
					$end_time = mktime(0,0,0,date('m'),1,date('Y'));
					break;
			}
			//查找所有
			$map['m.id'] = array("in","130,131,132,133,134,135,136,137,138,140,141,280,344,391,406");
			$member = M('members m')->join("{$this->pre}member_info mi on mi.uid=m.id")->field('m.id,mi.real_name')->where($map)->select();
			//推广人数
			$condition['mi.uid'] = array("in","130,131,132,133,134,135,136,137,138,140,141,280,344,391,406");
			$condition['m.reg_time'] = array("between","{$start_time},{$end_time}");
			$recommend=M('member_info mi')
				->join("{$this->pre}members m on mi.uid=m.recommend_id")
				->where($condition)
				->field('mi.uid as id,count(m.recommend_id) as recommend_number')
				->order('mi.uid')
				->group('mi.uid')
				->select(); 
			//推广金额
			$condition2['m.id'] = array("in","130,131,132,133,134,135,136,137,138,140,141,280,344,391,406");
			$condition2['li.invest_time'] = array("between","{$start_time},{$end_time}");
			$loan_invest=M('members m')
				->join("{$this->pre}members m2 on m.id=m2.recommend_id or m.id=m2.id")
				->join("{$this->pre}loan_invest li on m2.id=li.invest_uid")
				->where($condition2)
				->field('m.id,sum(li.invest_amount) as recommend_money')
				->order('m.id')
				->group('m.id')
				->select();
			//合并数组
			for($i=0;$i<count($member);$i++){
				$recommend_number = 0;
				$recommend_money = 0;
				for($a=0;$a<count($recommend);$a++){
					if($recommend[$a]['id']==$member[$i]['id']){
						$recommend_number = $recommend[$a]['recommend_number'];
						continue;
					}
				}
				for($b=0;$b<count($loan_invest);$b++){
					if($loan_invest[$b]['id'] == $member[$i]['id']){
						$recommend_money = $loan_invest[$b]['recommend_money'];
						continue;
					}
				}
				$number[] = $member[$i]['recommend_number'] = $recommend_number;
				$money[] = $member[$i]['recommend_money'] = $recommend_money;
			}
			
			if($type==1){
				array_multisort($number, SORT_DESC, $member);
			}else{
				array_multisort($money, SORT_DESC, $member);
			}
			
			$this->assign("Des",$member);
			$this->assign("type",$type);
			$this->assign("period",$period);
			$this->display();
        }

		public function promotion(){
			$id=intval($_GET['id']);
			$period=intval($_GET['period']);
			switch ($period){
				case 1:
					$start_time = 1429757860;
					$end_time = time();
					break; 
				case 2:
					$start_time = mktime(0,0,0,date('m'),1,date('Y'));
					$end_time = time();
					break;
				case 3;
					$start_time = strtotime('-1 month',mktime(0,0,0,date('m'),1,date('Y')));
					$end_time = mktime(0,0,0,date('m'),1,date('Y'));
					break;
			}
			$condition['recommend_id']=$id;
			$condition['id']=$id;
			$condition['_logic'] = 'OR';
			$count = M('members')
				->field('id,user_name')
				->where($condition)
				->select();
			for($a=0;$a<count($count);$a++){
				$map['invest_uid'] = $count[$a]['id'];
				$map['invest_time'] = array("between","{$start_time},{$end_time}");
				$sum = M('loan_invest')
					->field("sum(invest_amount) as money")
					->where($map)
					->find();
				$count[$a]['money'] = $sum['money'] == NULL?0:$sum['money'];
			}
			$this->assign("count", $count);
			$this->display();
        }
        
    }
?>
