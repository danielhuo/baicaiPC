<?php
// 本类由系统自动生成，仅供测试用途
class BillAction extends HCommonAction {
	
	public function apply(){
		$this->display();
	}
	
	
	public function save(){
			$data["telephone"]=$_POST["telephone"];
			$data['back_time']=strtotime($_POST["back_time"]);
			$data['bill_amount']=$_POST["price"];
			$data['access_name']=$_POST["name"];
			
		
			$regExp="/^[1][3-8]+\\d{9}/";
			if($data["telephone"]==""||$data["back_time"]==""||$data["bill_amount"]==""||$data["access_name"]==""){
				ajaxmsg("请将数据填写完整",1); 
				exit;
				
			}
			if(!preg_match($regExp,$data["telephone"])){
				ajaxmsg("号码格式出错",2);  
				exit; 
			}
			
			$data["status"]=0;
			$data["access_time"]=time();
			$uid=M("accesstel")->add($data);
			if($uid>0){
				ajaxmsg($data["access_time"],3);
			}	
			
		//$this->success("提交号码成功","__URL__/index");
			
		
	}
}
?>