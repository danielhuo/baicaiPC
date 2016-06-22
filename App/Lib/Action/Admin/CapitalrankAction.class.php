<?php
class CapitalrankAction extends ACommonAction
{
    /**
    +----------------------------------------------------------
    * Ä¬ÈÏ²Ù×÷
    +----------------------------------------------------------
    */
   public function index()
    {	
	
		$map = 'type=6';
		$last = time();
		$status = $_REQUEST['status'];
		
		$startdate = $_REQUEST['startdate'];
		$startdate = $_REQUEST['startdate'];
		$user_name = $_REQUEST['user_name'];
		$search = array();
		$search['status'] =  $status;
		if($status == 'w'){
			$sta_time = strtotime("-1 week");
			$map .= ' and add_time between ' . $sta_time . ' and ' . $last;
			
		}
		if($status == 'm'){
			$sta_time = strtotime("-1 month");
			$map .= ' and add_time between ' . $sta_time . ' and ' . $last;
			
		}
		if($status == 'y'){
			$sta_time = strtotime("-1 year");
			$map .= ' and add_time between ' . $sta_time . ' and ' . $last;
			
		}
		if($startdate){
			$map .= ' and b.add_time   > ' . $startdate;
			$search['startdate'] =  $startdate;
		}
		if($enddate){
			$map .= ' and b.add_time   < ' . $enddate;
			$search['enddate'] =  $enddate;
		}
		
		if($user_name){
			$map .= ' and m.user_name   = "' . $user_name . '"';
			$search['user_name'] =  $user_name;
		}
		
		import("ORG.Util.Page");
		$count = M("member_moneylog b")->join("{$this->pre}members m ON m.id=b.uid")->where($map)->count('DISTINCT b.uid');
		
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		
		$list = M("member_moneylog b")->join("{$this->pre}members m ON m.id=b.uid")->field('m.user_name,b.add_time,abs(sum(b.affect_money)) as money')->order('money DESC')->where($map)->limit($Lsql)->group('b.uid')->select();
		
		$this->assign("list", $list);
		 $this->assign("pagebar", $page);
		$this->assign("search", $search);
		$this->assign("query", http_build_query($search));
        $this->display();
    }

}
?>