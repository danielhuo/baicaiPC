<?php
// 本类由系统自动生成，仅供测试用途
class BillinvestAction extends MCommonAction {

    public function index(){
		$this->display();
    }
    public function summary(){
		$uid = $this->uid;
		$pre = C('DB_PREFIX');
		
		$this->assign("dc",M('investor_detail')->where("investor_uid = {$this->uid}")->sum('substitute_money'));
		$this->assign("mx",getMemberBorrowScan($this->uid));
		$data['html'] = $this->fetch();
		exit(json_encode($data));
    }
	
	public function investing(){
		
		$map['invest_uid'] = $this->uid;
		$map['status'] =2;
		
		$list = getInvestList($map,15);

		
		$this->assign("list",$list['list']);
		$this->assign("pagebar",$list['page']);
		$this->assign("total",$list['total_money']);
		$this->assign("num",$list['total_num']);
		$data['html'] = $this->fetch();
		exit(json_encode($data));
	}

	public function investbacking(){
		$map['invest_uid'] = $this->uid;
		$map['status'] = 7;
        
        
		$list = getInvestList($map,15);
        //$list = $this->getTendBacking();
		$this->assign("list",$list['list']);
		$this->assign("pagebar",$list['page']);
		$this->assign("total",$list['total_money']);
		$this->assign("num",$list['total_num']);
		//$this->display("Public:_footer");

        $this->assign('uid', $this->uid);
		
		$data['html'] = $this->fetch();
		exit(json_encode($data));
	}



	public function investdone(){
		//$map['i.investor_uid'] = $this->uid;
//		$map['i.status'] = array("in","5,6");
		$map['invest_uid'] = $this->uid;
		$map['status'] = 8;

		$list = getInvestList($map,15);
		$this->assign("list",$list['list']);
		$this->assign("pagebar",$list['page']);
		$this->assign("total",$list['total_money']);
		$this->assign("num",$list['total_num']);
		//$this->display("Public:_footer");

		$data['html'] = $this->fetch();
		exit(json_encode($data));
	}	
}