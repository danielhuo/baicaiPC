<?php
// 本类由系统自动生成，仅供测试用途
class LoanAction extends MCommonAction {

    public function index(){
		$map['loan_uid'] = $this->uid;
		$map['status'] = $_GET['status']?$_GET['status']:0;
		$list = getLoanList($map,10);
	
		$this->assign("list",$list['list']);
		$this->assign("pagebar",$list['page']);
		$this->assign("status",$map['status']);
	
		$data['html'] = $this->fetch();
		exit(json_encode($data));
    }
}