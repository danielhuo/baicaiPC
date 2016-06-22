<?php
// 本类由系统自动生成，仅供测试用途
class MytradeAction extends MCommonAction {

    public function index(){
		$this->assign('type',$_GET['type']);
		if($_GET['type']=="loandetail"){
			$this->assign('id',$_GET['id']);
		}
		$this->display();
    }

    public function summary(){
		$pre = C('DB_PREFIX');
		
		$this->assign("mx",getMemberBorrowScan($this->uid));
		$data['html'] = $this->fetch();
		exit(json_encode($data));
    }
	
	public function loan(){
		$map['loan_uid'] = $this->uid;
		$map['status'] = $_GET['status']?$_GET['status']:0;
		$list = getLoanList($map,10);
	
		$this->assign("list",$list['list']);
		$this->assign("pagebar",$list['page']);
		$this->assign("status",$map['status']);
	
		$data['html'] = $this->fetch();
		exit(json_encode($data));
	}
	public function loandetail(){
		$id=$_GET['id'];
		if(!$id) return;
		$loan=M('loan')->find($id);
		$map['l.id'] = $id;
		$list = getLoaninvestList($map,10);
		$this->assign("loan",$loan);
		$this->assign("list",$list['list']);
		
		$this->assign("pagebar",$list['page']);
		$this->assign("status",$list[0]['status']);
		//$this->display("Public:_footer");
		
		$data['html'] = $this->fetch();
		exit(json_encode($data));
	}
	public function loaninvest(){
		
		$map['li.invest_uid'] = $this->uid;
		$map['l.status'] = $_GET['status']?$_GET['status']:2;

		$list = getLoaninvestList($map,10);
		foreach ($list['list'] as $k=> $v) {
			$list['list'][$k]['begin_time']=$v['begin_time']?$v['begin_time']:$v['collect_time'];
			$list['list'][$k]['invest_interest']=$v['invest_interest']>0?$v['invest_interest']:"按实投天数算息";
		}
		$this->assign("list",$list['list']);
		$this->assign("pagebar",$list['page']);
		$this->assign("total",$list['total_money']);
		$this->assign("num",$list['total_num']);
		$this->assign("status",$map['status']);
		//$this->display("Public:_footer");
		
		$data['html'] = $this->fetch();
		exit(json_encode($data));
	}

}