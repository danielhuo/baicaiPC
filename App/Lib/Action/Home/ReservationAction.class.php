<?php
// 本类由系统自动生成，仅供测试用途
class ReservationAction extends HCommonAction {
	 public function index()
    {
       		
		//$parm['map']="b.status in(2,4,7,8)";
		$parm['orderby']="r.id DESC";
	    $parm['pagesize'] = 5;
		$parm['limit']=5;
		$list = getReservationList($parm);
		 //dump($list);
		$this->assign("list",$list);
	    $this->display();
    }
	
	
	
	
    public function detail()
	{
		$pre = C('DB_PREFIX');
		$id = intval($_GET['id']);
		$reservation = M("reservation_project")->find($id);  
		$reservation['need'] = $reservation['amount'] - $reservation['has_borrow'];
		$list = M("reservation_invest r")->field("m.user_name,r.invest_amount")->join("{$pre}members m ON r.invest_uid = m.id")->where("r.r_id={$id}")->select();
		
		
		$this->assign("reservation",$reservation);	
		$this->assign("list",$list);
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