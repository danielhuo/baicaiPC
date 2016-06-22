<?php
// 管理员管理
class TeammoneyAction extends ACommonAction
{
    /**
    +----------------------------------------------------------
    * 默认操作
    +----------------------------------------------------------
    */
    public function index()
    {
        import("ORG.Util.Page");
		if($_REQUEST['user_name']){
			$map['user_name'] = urldecode($_REQUEST['user_name']);
			$search['user_name'] = $map['user_name'];
		}
		if($_REQUEST['start_time'] && $_REQUEST['end_time']){
		    $timespan = strtotime(urldecode($_REQUEST['start_time'])).",".strtotime(urldecode($_REQUEST['end_time']));
			$tmap['add_time'] = array("between",$timespan);
			$search['start_time'] = urldecode($_REQUEST['start_time']);		
			$search['end_time'] = urldecode($_REQUEST['end_time']);
		}elseif(!empty($_REQUEST['start_time'])){
			$xtime = strtotime(urldecode($_REQUEST['start_time']));
			$tmap['add_time'] = array("gt",$xtime);
			$search['start_time'] = $xtime;	
		}elseif(!empty($_REQUEST['end_time'])){
			$xtime = strtotime(urldecode($_REQUEST['end_time']));
			$tmap['add_time'] = array("lt",$xtime);
			$search['end_time'] = $xtime;	
		}
		$map['u_group_id'] = 25;
		$AdminU = M('ausers');
		$page_size = ($page_szie==0)?C('ADMIN_PAGE_SIZE'):$page_szie;
		
		
		$count  = $AdminU->where($map)->count(); // 查询满足要求的总记录数   
		$Page = new Page($count,$page_size); // 实例化分页类传入总记录数和每页显示的记录数   
		$show = $Page->show(); // 分页显示输出
		   
		$fields = "id,user_name,u_group_id,real_name,is_ban,area_name,is_kf,qq,phone,user_word";
		$order = "id DESC";
		
		$list = $AdminU->field(true)->where($map)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();

		$AdminUserList = $list;
		
		$datag = get_global_setting();
		$total = 0;
		foreach($AdminUserList as $key => $v){
			$idlist = array();
			$aid = M('ausers')->field('id')->where("parent={$v['id']}")->select();
			foreach($aid as $ka => $va){
			    $mid = M('members')->field('id')->where("recommend_id={$va['id']}")->select();
			    foreach($mid as $km =>$vm){
			        array_push($idlist,$vm['id']);
			    }
			}
			//`mxl:weighted`
			$sidlist = implode(",", $idlist);
			$map1 = "v.investor_uid in ( {$sidlist} )";
			$field1 = "sum(v.investor_capital * i.borrow_duration / if (i.repayment_type = 1, 360, 12)) as total";
			$sql = "SELECT {$field1} FROM `{$this->pre}borrow_investor` v JOIN `{$this->pre}borrow_info` i ON v.borrow_id = i.id WHERE ( {$map1} )";
			$total1 = M()->query($sql);
			$map2 = "tv.investor_uid in ( {$sidlist} )";
			$field2 = "sum(tv.investor_capital * ti.borrow_duration / if (ti.repayment_type = 1, 360, 12)) as total";
			$sql = "SELECT {$field2} FROM `{$this->pre}transfer_borrow_investor` tv JOIN `{$this->pre}transfer_borrow_info` ti ON tv.borrow_id = ti.id WHERE ( {$map2} )";
			$total2 = M()->query($sql);
			$AdminUserList[$key]['money'] = ($total1[0]['total'] + $total2[0]['total']) * $datag['broker_push_money'] * $datag['team_push_money'];
			//`mxl:weighted`
			$total = $total + $AdminUserList[$key]['money'];
			
		}

		$this->assign('position', '团队长提成统计');
		$this->assign('pagebar', $show);
		$this->assign('total', $total);
		$this->assign('admin_list', $AdminUserList);
		$this->assign("search",$search);
        $this->assign("query", http_build_query($search));
        $this->display();
    }
	//经纪人列表
	public function brokerlist()
    {
        import("ORG.Util.Page");
		if($_REQUEST['user_name']){
		    $map['user_name'] = urldecode($_REQUEST['user_name']);
		}
		if($_REQUEST['start_time'] && $_REQUEST['end_time']){
		    $timespan = strtotime(urldecode($_REQUEST['start_time'])).",".strtotime(urldecode($_REQUEST['end_time']));
			$map['add_time'] = array("between",$timespan);
			$search['start_time'] = urldecode($_REQUEST['start_time']);	
			$search['end_time'] = urldecode($_REQUEST['end_time']);
		}elseif(!empty($_REQUEST['start_time'])){
			$xtime = strtotime(urldecode($_REQUEST['start_time']));
			$map['add_time'] = array("gt",$xtime);
			$search['start_time'] = $xtime;	
		}elseif(!empty($_REQUEST['end_time'])){
			$xtime = strtotime(urldecode($_REQUEST['end_time']));
			$map['add_time'] = array("lt",$xtime);
			$search['end_time'] = $xtime;	
		}
		if($_REQUEST['id']){
			
			$team = M('ausers')->field('id,user_name,real_name')->where("id={$_REQUEST['id']}")->find();
		    $map['parent'] = $_REQUEST['id'];
		}
		$map['u_group_id'] = 26;
		$AdminU = M('ausers');
		$page_size = ($page_szie==0)?C('ADMIN_PAGE_SIZE'):$page_szie;
		
		
		$count  = $AdminU->where($map)->count(); // 查询满足要求的总记录数   
		$Page = new Page($count,$page_size); // 实例化分页类传入总记录数和每页显示的记录数   
		$show = $Page->show(); // 分页显示输出
		   
		$fields = "id,user_name,u_group_id,real_name,is_ban,area_name,is_kf,qq,phone,user_word";
		$order = "id DESC";
		
		$list = $AdminU->field(true)->where($map)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();

		$AdminUserList = $list;
		
		$datag = get_global_setting();
		$total = 0;
		foreach($AdminUserList as $key => $v){
			$idlist = array();
			$mid = M('members')->field('id')->where("recommend_id={$v['id']}")->select();
			foreach($mid as $km =>$vm){
			    array_push($idlist,$vm['id']);
			}
			//`mxl:weighted`
			$sidlist = implode(",", $idlist);
			$map1 = "v.investor_uid in ( {$sidlist} )";
			$field1 = "sum(v.investor_capital * i.borrow_duration / if (i.repayment_type = 1, 360, 12)) as total";
			$sql = "SELECT {$field1} FROM `{$this->pre}borrow_investor` v JOIN `{$this->pre}borrow_info` i ON v.borrow_id = i.id WHERE ( {$map1} )";
			$total1 = M()->query($sql);
			$map2 = "tv.investor_uid in ( {$sidlist} )";
			$field2 = "sum(tv.investor_capital * ti.borrow_duration / if (ti.repayment_type = 1, 360, 12)) as total";
			$sql = "SELECT {$field2} FROM `{$this->pre}transfer_borrow_investor` tv JOIN `{$this->pre}transfer_borrow_info` ti ON tv.borrow_id = ti.id WHERE ( {$map2} )";
			$total2 = M()->query($sql);
			$AdminUserList[$key]['money'] = ($total1[0]['total'] + $total2[0]['total']) * $datag['broker_push_money'] * $datag['team_push_money'];
			//`mxl:weighted`
			$total = $total + $AdminUserList[$key]['money'];
			
		}

		$this->assign('position', '团队长提成统计');
		$this->assign('pagebar', $show);
		$this->assign('total', $total);
		$this->assign('admin_list', $AdminUserList);
		$this->assign("search",$search);
		$this->assign("teamid",$_REQUEST['id']);
		$this->assign("team",$team);
		
        $this->display();
    }
	
	//团队长提成统计导出
	public function export(){
		import("ORG.Io.Excel");
		if($_REQUEST['user_name']){
			$map['user_name'] = urldecode($_REQUEST['user_name']);
			$search['user_name'] = $map['user_name'];
		}
		if($_REQUEST['start_time'] && $_REQUEST['end_time']){
		    $timespan = strtotime(urldecode($_REQUEST['start_time'])).",".strtotime(urldecode($_REQUEST['end_time']));
			$tmap['add_time'] = array("between",$timespan);
			$search['start_time'] = urldecode($_REQUEST['start_time']);	
			$search['end_time'] = urldecode($_REQUEST['end_time']);
		}elseif(!empty($_REQUEST['start_time'])){
			$xtime = strtotime(urldecode($_REQUEST['start_time']));
			$tmap['add_time'] = array("gt",$xtime);
			$search['start_time'] = $xtime;	
		}elseif(!empty($_REQUEST['end_time'])){
			$xtime = strtotime(urldecode($_REQUEST['end_time']));
			$tmap['add_time'] = array("lt",$xtime);
			$search['end_time'] = $xtime;	
		}
		$map['u_group_id'] = 25;
		   
		$fields = "id,user_name,u_group_id,real_name,is_ban,area_name,is_kf,qq,phone,user_word";
		$order = "id DESC";
		
		$list = M('ausers')->field(true)->where($map)->order($order)->select();
		
		$AdminUserList = $list;
		
		$datag = get_global_setting();
		$total = 0;
		foreach($AdminUserList as $key => $v){
			$idlist = array();
			$aid = M('ausers')->field('id')->where("parent={$v['id']}")->select();
			foreach($aid as $ka => $va){
			    $mid = M('members')->field('id')->where("recommend_id={$va['id']}")->select();
			    foreach($mid as $km =>$vm){
			        array_push($idlist,$vm['id']);
			    }
			}
			//`mxl:weighted`
			$sidlist = implode(",", $idlist);
			$map1 = "v.investor_uid in ( {$sidlist} )";
			$field1 = "sum(v.investor_capital * i.borrow_duration / if (i.repayment_type = 1, 360, 12)) as total";
			$sql = "SELECT {$field1} FROM `{$this->pre}borrow_investor` v JOIN `{$this->pre}borrow_info` i ON v.borrow_id = i.id WHERE ( {$map1} )";
			$total1 = M()->query($sql);
			$map2 = "tv.investor_uid in ( {$sidlist} )";
			$field2 = "sum(tv.investor_capital * ti.borrow_duration / if (ti.repayment_type = 1, 360, 12)) as total";
			$sql = "SELECT {$field2} FROM `{$this->pre}transfer_borrow_investor` tv JOIN `{$this->pre}transfer_borrow_info` ti ON tv.borrow_id = ti.id WHERE ( {$map2} )";
			$total2 = M()->query($sql);
			$AdminUserList[$key]['money'] = ($total1[0]['total'] + $total2[0]['total']) * $datag['broker_push_money'] * $datag['team_push_money'];
			$AdminUserList[$key]['capital'] = $total1[0]['total'] + $total2[0]['total'];
			//`mxl:weighted`
			$AdminUserList[$key]['num'] = count($idlist);//客户总数
		}
		
		$row=array();
		$row[0]=array('序号','团队长账号','真实姓名','资产总额','客户总数','交易提成');
		$i=1;
		foreach($AdminUserList as $v){
				$row[$i]['i'] = $i;
				$row[$i]['user_name'] = $v['user_name'];
				$row[$i]['real_name'] = $v['real_name'];
				$row[$i]['capital'] = $v['capital'];
				$row[$i]['num'] = $v['num'];
				$row[$i]['total'] = $v['money'];
				$i++;
		}
		
		$xls = new Excel_XML('UTF-8', false, 'datalist');
		$xls->addArray($row);
		$xls->generateXML("tuanduizhangticheng");
	}


	//投资人列表
    public function investorlist()
    {
        import("ORG.Util.Page");
		if($_REQUEST['user_name']){
		    $map['user_name'] = urldecode($_REQUEST['user_name']);
		}
		if($_REQUEST['id']){
			$brokerid = urldecode($_REQUEST['id']);
			$broker = M('ausers')->field("user_name,real_name,parent")->where("id={$brokerid}")->find();
			$team = M('ausers')->field('id,user_name,real_name')->where("id={$broker['parent']}")->find();
			$map['recommend_id'] = $brokerid;
		}
		$members = M('members m');
		$page_size = ($page_szie==0)?C('ADMIN_PAGE_SIZE'):$page_szie;
		$count  = $members->where($map)->count();
		$Page = new Page($count,$page_size); // 实例化分页类传入总记录数和每页显示的记录数   
		$show = $Page->show(); // 分页显示输出
		
		$fields = "m.id,m.user_name,mi.real_name,mi.idcard,m.reg_time";
		$order = "id DESC";
		$mlist = $members->join("{$this->pre}member_info mi ON mi.uid=m.id")->field($field)->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
		$datag = get_global_setting();
		foreach($mlist as $km =>$vm){
			//`mxl:weighted`
			$map1 = "v.investor_uid = {$vm['id']}";
			$field1 = "sum(v.investor_capital * i.borrow_duration / if (i.repayment_type = 1, 360, 12)) as total";
			$sql = "SELECT {$field1} FROM `{$this->pre}borrow_investor` v JOIN `{$this->pre}borrow_info` i ON v.borrow_id = i.id WHERE ( {$map1} )";
			$total1 = M()->query($sql);
			$map2 = "tv.investor_uid = {$vm['id']}";
			$field2 = "sum(tv.investor_capital * ti.borrow_duration / if (ti.repayment_type = 1, 360, 12)) as total";
			$sql = "SELECT {$field2} FROM `{$this->pre}transfer_borrow_investor` tv JOIN `{$this->pre}transfer_borrow_info` ti ON tv.borrow_id = ti.id WHERE ( {$map2} )";
			$total2 = M()->query($sql);
			$mlist[$km]['money'] = ($total1[0]['total'] + $total2[0]['total']) * $datag['broker_push_money'] * $datag['team_push_money'];
			//`mxl:weighted`
			$total = $total + $mlist[$km]['money'];
		}
		
		$this->assign('position', '团队长提成统计');
		$this->assign('pagebar', $show);
		$this->assign('total', $total);
		$this->assign('mlist', $mlist);
		$this->assign('broker', $broker);
		$this->assign('brokerid', $brokerid);
		$this->assign('team', $team);
        $this->display();
    }
    
}
?>
