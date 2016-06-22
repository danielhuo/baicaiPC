<?php
// 本类由系统自动生成，仅供测试用途
class CapitalAction extends MCommonAction {

    public function index(){
		$this->display();
    }

    public function summary(){
		$vlist = getMemberMoneySummary($this->uid);
		$this->assign("vo",$vlist);
		

        $this->assign('pcount', get_personal_count($this->uid)); 

		$minfo =getMinfo($this->uid,true);
        $this->assign("minfo",$minfo); 
        $this->assign('benefit', get_personal_benefit($this->uid));   //收入
        $this->assign('out', get_personal_out($this->uid));      //支出
		////////////////////////////////////////////////////////////////////
		$data['html'] = $this->fetch();
		exit(json_encode($data));
    }

    public function detail(){
		$logtype = C('MONEY_LOG');
		$this->assign('log_type',$logtype);

		$map['uid'] = $this->uid;
		if($_GET['start_time']&&$_GET['end_time']){
			$_GET['start_time'] = strtotime($_GET['start_time']." 00:00:00");
			$_GET['end_time'] = strtotime($_GET['end_time']." 23:59:59");
			
			if($_GET['start_time']<$_GET['end_time']){
				$map['add_time']=array("between","{$_GET['start_time']},{$_GET['end_time']}");
				$search['start_time'] = $_GET['start_time'];
				$search['end_time'] = $_GET['end_time'];
			}
		}
		if(!empty($_GET['log_type'])){
				$map['type'] = intval($_GET['log_type']);
				$search['log_type'] = intval($_GET['log_type']);
		}
		$parm['map'] =$map;
	    $parm['pagesize'] =20;
		$list = getMoneyLog($parm);
		

		$this->assign('search',$search);
		$this->assign("list",$list['list']);		
		$this->assign("pagebar",$list['page']);	
        $this->assign("query", http_build_query($search));
		$data['html'] = $this->fetch();
		exit(json_encode($data));
    }
	
	public function export(){
		import("ORG.Io.Excel");

		$map=array();
		$map['uid'] = $this->uid;
		if($_GET['start_time']&&$_GET['end_time']){
			$_GET['start_time'] = strtotime($_GET['start_time']." 00:00:00");
			$_GET['end_time'] = strtotime($_GET['end_time']." 23:59:59");
			
			if($_GET['start_time']<$_GET['end_time']){
				$map['add_time']=array("between","{$_GET['start_time']},{$_GET['end_time']}");
				$search['start_time'] = $_GET['start_time'];
				$search['end_time'] = $_GET['end_time'];
			}
		}
		if(!empty($_GET['log_type'])){
				$map['type'] = intval($_GET['log_type']);
				$search['log_type'] = intval($_GET['log_type']);
		}

		$list = getMoneyLog($map,100000);
		
		$logtype = C('MONEY_LOG');
		$row=array();
		$row[0]=array('序号','发生日期','类型','影响金额','可用余额','冻结金额','待收金额','说明');
		$i=1;
		foreach($list['list'] as $v){
				$row[$i]['i'] = $i;
				$row[$i]['uid'] = date("Y-m-d H:i:s",$v['add_time']);
				$row[$i]['card_num'] = $v['type'];
				$row[$i]['card_pass'] = $v['affect_money'];
				$row[$i]['card_mianfei'] = ($v['account_money']+$v['back_money']);
				$row[$i]['card_mianfei0'] = $v['freeze_money'];
				$row[$i]['card_mianfei1'] = $v['collect_money'];
				$row[$i]['card_mianfei2'] = $v['info'];
				$i++;
		}
		
		$xls = new Excel_XML('UTF-8', false, 'moneyLog');
		$xls->addArray($row);
		$xls->generateXML("moneyLog");
	}
	
	public function repaylist(){
		import("ORG.Util.Page");
		$id = $this->uid;
		$count =  M("loan_repay r")
			->join("{$this->pre}loan l ON r.lid = l.id")
			->field("r.lid,l.loan_name,l.pay_periods,r.deadline,r.num_period,r.repay_amount,r.status,r.deadline")
			->where("l.loan_uid={$id}")
			->count('r.id');
		$p = new Page($count,10);
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		$order= "r.status ,r.lid,r.deadline";
		
		$repaylist = M("loan_repay r")
			->join("{$this->pre}loan l ON r.lid = l.id")
			->field("r.lid,l.loan_name,l.pay_periods,r.deadline,r.num_period,r.repay_amount,r.status")
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
		$p = new Page($count,10);
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		$order= "p.status ,p.lid ,p.deadline";
		
		$returnlist = M("loan_invest_return r")
			->join("{$this->pre}loan_repay p ON p.id=r.rid")
			->join("{$this->pre}loan l ON p.lid=l.id")
			->join("{$this->pre}loan_invest i ON i.id=r.iid")
			->field("p.lid,p.deadline,l.loan_name,l.pay_periods,p.num_period,r.return_amount,p.status")
			->order($order)
			->limit($Lsql)
			->where("i.invest_uid = {$id}")
			->select();
		$this->assign("returnlist",$returnlist);
			$this->assign("pagebar", $page);
		$this->display();
		
	}

}