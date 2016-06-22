<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends HCommonAction {
		
    public function index(){
		//判断是否是手机推荐注册
		if($_GET['invite']){		
					session('invite',$_GET['invite']);
				}
		//网站公告
		$parm['type_id'] = 9;
		$parm['limit'] =4;
		$this->assign("noticeList",getArticleList($parm));

		//理财产品
		$parm['map']="l.status in(2,4,6,7,8)";
		$parm['orderby']="l.status ASC,l.id DESC";
	    $parm['pagesize'] = 5;
		$parm['limit']=5;
		$list = getLoanList($parm);
		$this->assign("listLoan",$list);
        $this->display();
    
    
    }	
	
	public function updateMarq(){
		//选择最新的贷款
		$searchMap = array();
		$searchMap['b.borrow_status']=array("in",'2');
		$searchMap['b.is_tuijian']=array("in",'0,1');
		$parm=array();
		$parm['map'] = $searchMap;
		$parm['limit'] = 1;
		$parm['orderby']="b.add_time DESC,b.borrow_status ASC,b.id DESC";
		$data = getBorrowList($parm);
		exit(json_encode($data));
    }
	
	
	
public function updateMarq2(){
		//选择最新的贷款
		$searchMap = array();
		$searchMap['b.borrow_status']=array("in",'2,7');
		$searchMap['b.is_tuijian']=array("in",'0,1');
		$parm=array();
		$parm['map'] = $searchMap;
		$parm['limit'] = 1;
		$parm['orderby']="b.add_time DESC,b.borrow_status ASC,b.id DESC";
		$data = getBorrowList($parm);
		exit(json_encode($data));	
    }
	
	public function updateMarq3(){
		//选择最新的贷款
		$searchMap = array();
		$searchMap['b.borrow_status']=array("in",'2,7');
		$searchMap['b.is_tuijian']=array("in",'0,1');
		$parm=array();
		$parm['map'] = $searchMap;
		$parm['limit'] = 1;
		$parm['orderby']="b.add_time DESC,b.borrow_status ASC,b.id DESC";
		$data = getBorrowList($parm);
		exit(json_encode($data));
		
    }
	
	//统计图
	public function count(){
		$time2 = time();
		$time1 = time() - 518200;
		$start_time = date("Y/m/d",$time1);
		$end_time = date("Y/m/d",$time2); 
		$start_time1 = strtotime(urldecode($start_time));
		$end_time1 = strtotime(urldecode($end_time));
		$bmoneys = array();
		$member_num=array();
		for ($time=$start_time1;$time <= $end_time1;$time +=86400){
			$time1=$time+86400;
			$money = M('borrow_info')->where("first_verify_time >= {$time} and first_verify_time < {$time1} and (borrow_status=6 or borrow_status=7)")->sum('borrow_money');
			$money=$money?$money:0;
			$num = M('members')->where("reg_time >= {$time} and reg_time < {$time1}")->count("id");
			$num=$num?$num:0;
			
			$member_num[] = $num;
			$bmoneys[]=$money;	
		}
		$this->assign("member_num",$member_num);
		$this->assign("bmoneys",$bmoneys);
		$this->assign("start_time",$start_time);
		$this->assign("end_time",$end_time);
		$this->display();
	}
	
	public function countmoney(){
		$start_time = strtotime(urldecode($_REQUEST['start_time']));
		$end_time = strtotime(urldecode($_REQUEST['end_time']));
		if ($start_time > $end_time) ajaxmsg('qq',1);
		$bmoneys = array();
		
		for ($time=$start_time;$time <= $end_time;$time +=86400){
			$time1=$time +86400;
			$money = M('borrow_info')->where("first_verify_time >= {$time} and first_verify_time < {$time1} and (borrow_status=6 or borrow_status=7)")->sum('borrow_money');
			$money=$money?$money:0;
			
			$bmoneys[]=$money;	
		}
		
		$this->ajaxReturn($bmoneys,'JSON');


		
	}
	public function countmember(){
		$start_time = strtotime(urldecode($_REQUEST['start_time1']));
		$end_time = strtotime(urldecode($_REQUEST['end_time1']));
		if ($start_time > $end_time) ajaxmsg('qq',1);
		$member_num = array();
		
		for ($time=$start_time;$time <= $end_time;$time +=86400){
			$time1=$time +86400;
			$num = M('members')->where("reg_time >= {$time} and reg_time < {$time1}")->count("id");
			$num=$num?$num:0;
			$member_num[] = $num;
		}
		
		$this->ajaxReturn($member_num,'JSON');
	}
	 
	 
 }
