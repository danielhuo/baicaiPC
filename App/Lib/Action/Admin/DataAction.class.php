<?php
// 全局设置
class DataAction extends ACommonAction{
	public function collect(){
		if(!empty($_REQUEST['uid'])){
			$user_name = M("members")->getFieldById(intval($_REQUEST['uid']),'user_name');
			if($user_name){
				$search['uid'] = intval($_REQUEST['uid']);
				$search['user_name'] = $user_name;
			}
		}
		if(!empty($_REQUEST['uname'])){
			$uid = M("members")->getFieldByUserName(text($_REQUEST['uname']),'id');
			if($uid){
				$search['uid'] = $uid;
				$search['user_name'] = text($_REQUEST['uname']);
			}
		}

		if($search['uid']){
			$vo = M('member_money')->where(" uid={$search[uid]} ")->find();
			$money['account_money'] = $vo['account_money'] + $vo['back_money'];
			$money['money_freeze'] = $vo['money_freeze'];
			$money['collect'] = M('investor_detail')->where("investor_uid={$search[uid]} AND status=7")->sum("capital+interest-interest_fee");
			$money['collect'] += M('transfer_investor_detail')->where("investor_uid={$search[uid]} AND status=7")->sum("capital+interest-interest_fee");
			$money['needpay'] = -(M('investor_detail')->where("borrow_uid={$search[uid]} AND status in(4,7)")->sum("capital+interest") );
			$money['total_money'] = $money['account_money'] + $money['money_freeze'] + $money['collect'] + $money['needpay'];

			$list1 = M('investor_detail')->field("sum(capital+interest-interest_fee) AS money,borrow_id AS id,sum(capital) AS capital,sum(interest) AS interest,sum(interest_fee) AS interest_fee")->where("investor_uid={$search[uid]} AND status=7")->group("borrow_id")->select();
			$list1All = M('investor_detail')->field("sum(capital+interest-interest_fee) AS money,count(DISTINCT borrow_id) AS num,sum(capital) AS capital,sum(interest) AS interest,sum(interest_fee) AS interest_fee")->where("investor_uid={$search[uid]} AND status=7")->group("investor_uid")->find();

			$list2 = M('investor_detail')->field("sum(capital+interest) AS money,borrow_id AS id,sum(capital) AS capital,sum(interest) AS interest")->where("borrow_uid={$search[uid]} AND status in(4,7)")->group("borrow_id")->select();
			$list2All = M('investor_detail')->field("sum(capital+interest) AS money,count(DISTINCT borrow_id) AS num,sum(capital) AS capital,sum(interest) AS interest")->where("borrow_uid={$search[uid]} AND status in(4,7)")->group("borrow_uid")->find();
		}
		$this->assign("vo", $money);
		$this->assign("list1", $list1);
		$this->assign("list1All", $list1All);
		$this->assign("list2", $list2);
		$this->assign("list2All", $list2All);
        $this->assign("search", $search);
        $this->assign("type", C('MONEY_LOG'));
		$this->assign("xaction",ACTION_NAME);
		
        $this->display();
	}

	public function paytwo(){
		ini_set('memory_limit', '512M');
		$map = " l.uid<>1 ";

		if(isset($_REQUEST['type']) && $_REQUEST['type'] != ''){
			$map .= " AND l.type=".intval($_REQUEST['type']);
			$search['type'] = $map['l.type'];	
		}

		if(!empty($_REQUEST['start_time'])){
			$xtime = strtotime(urldecode($_REQUEST['start_time']));
			$map .= " AND l.add_time>$xtime";
			$search['start_time'] = $xtime;	
		}
		if(!empty($_REQUEST['end_time'])){
			$xtime = strtotime(urldecode($_REQUEST['end_time']));
			$map .= " AND l.add_time<$xtime";
			$search['end_time'] = $xtime;	
		}
		
		$field= 'l.id,l.uid,l.add_time,m.user_name,l.affect_money,l.freeze_money,l.account_money,l.target_uname,l.type,l.info';
		$order = "l.uid ASC,l.id DESC";
		$list = M('member_moneylog l')->field($field)->join("{$this->pre}members m ON m.id=l.uid")->where($map)->order($order)->select();

		$key = 0;
		$user = array();
		foreach ($list as $k => $v) {
			if ($v['uid']!=$user['uid']){
				unset($list[$key]);
				$key = $k;
				$user = $v;
			}else{
				if ($v['affect_money']==$user['affect_money'] && $v['type']==$user['type'] && $v['info']==$user['info'] && abs($v['add_time']-$user['add_time'])<300 ){
					$key = 0;
					$user = array();
				}else{
					unset($list[$key]);
					$key = $k;
					$user = $v;
				}
			}
		}
		array_pop($list);

		//分页处理
		import("ORG.Util.Page");
		$p = new Page(count($list), C('ADMIN_PAGE_SIZE'));
		$pagebar = $p->show();
		$start = $p->firstRow;
		$size = $p->listRows;

        $this->assign("bj", array("gt"=>'大于',"eq"=>'等于',"lt"=>'小于'));
        $this->assign("list",  array_slice($list,$start,$size));
        $this->assign("pagebar", $pagebar);
        $this->assign("search", $search);
		$this->assign("xaction",ACTION_NAME);
		$this->assign("type", C('MONEY_LOG'));
        $this->assign("query", http_build_query($search));
		
        $this->display();
    }
	
	
	public function investover(){
		$map= " b.borrow_status in(3,4,5,6,7,8,9) ";
		if(!empty($_REQUEST['start_time'])){
			$xtime = strtotime(urldecode($_REQUEST['start_time']));
			$map .= " AND b.add_time>$xtime";
			$search['start_time'] = $xtime;	
		}else{
			$xtime = strtotime("-1 month");
			$map .= " AND b.add_time>$xtime";
		}

		if(!empty($_REQUEST['end_time'])){
			$xtime = strtotime(urldecode($_REQUEST['end_time']));
			$map .= " AND b.add_time<$xtime";
			$search['end_time'] = $xtime;	
		}

		$field= 'b.id,b.borrow_uid,b.borrow_name,b.borrow_status,b.borrow_type,b.borrow_money,b.add_time';
		$Bconfig = require C("APP_ROOT")."Conf/borrow_config.php";
	 	$BType = $Bconfig['BORROW_TYPE'];
	 	$SType = $Bconfig['BORROW_STATUS'];

		$list = M('borrow_info b')->field($field)->where($map)->order("b.id DESC")->select();
		foreach($list as $k=>$v){
			$list[$k]['borrow_type_s'] = $BType[$v['borrow_type']];
			$list[$k]['borrow_status_s'] = $SType[$v['borrow_status']];

			$list[$k]['ru'] = M("member_moneylog")->where("type=17 AND uid={$v['borrow_uid']} AND info='第{$v['id']}号标复审通过，借款金额入帐' ")->sum("affect_money");
			
			if( !in_array($v['borrow_status'], array(3,5) )){
				$list[$k]['repayment_money'] = M('borrow_investor')->where(" borrow_id={$v[id]} ")->group("borrow_id")->sum("investor_capital");
				if( $list[$k]['repayment_money'] == $v['borrow_money'] && $list[$k]['ru'] == $v['borrow_money']){
					unset($list[$k]);
				}else{
					$list[$k]['mistake'] = ($list[$k]['repayment_money'] == $v['borrow_money']) ? $list[$k]['ru'] - $v['borrow_money'] : $list[$k]['repayment_money'] - $v['borrow_money'];
				}
			}else{
				$list[$k]['repayment_money'] = abs(M("member_moneylog")->where("type=6 AND info='对".$v['id']."号标进行投标' ")->sum("affect_money") );
				$list[$k]['fan'] = M("member_moneylog")->where("type=8 AND info='第".$v['id']."号标募集期内标未满,流标，返回冻结资金' ")->sum("affect_money");
				
				if( $list[$k]['repayment_money']==$list[$k]['fan'] && $list[$k]['ru']==0){
					unset($list[$k]);
				}else{
					$list[$k]['mistake'] = ($list[$k]['ru']==0) ? $list[$k]['fan'] - $list[$k]['repayment_money'] : $list[$k]['ru'];
				}
			}

		}

		//分页处理
		import("ORG.Util.Page");
		$p = new Page(count($list), C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$start = $p->firstRow;
		$size = $p->listRows;
		
        $this->assign("bj", array("gt"=>'大于',"eq"=>'等于',"lt"=>'小于'));
        $this->assign("list", array_slice($list,$start,$size));
        $this->assign("pagebar", $page);
        $this->assign("search", $search);
		$this->assign("xaction",ACTION_NAME);
        $this->assign("query", http_build_query($search));
		
        $this->display();
	}

	public function biaonum(){
		$Bconfig = require C("APP_ROOT")."Conf/borrow_config.php";
		$type = $Bconfig['BORROW_TYPE'];
		$list = array();
		foreach ($type as $k => $v) {
			$list[$k]['id'] = $k;
			$list[$k]['name'] = $v;
			$list[$k]['num'] = array();
			$list[$k]['money'] = array();
		}
		$list[6] = array('id'=>6,'name'=>"总计",'num'=>array(),'money'=>array() );

		$map = "";
		if($_REQUEST['borrow_status']>-1){
			$search['borrow_status'] = intval($_REQUEST['borrow_status']);
			$map = " borrow_status = ".$search['borrow_status']." AND " ; 
		}

		$smap[0] = $map." first_verify_time  > ".strtotime("-1 day");
		$smap[1] = $map." first_verify_time  > ".strtotime("-1 week");
		$smap[2] = $map." first_verify_time  > ".strtotime("-1 month");
		$smap[3] = $map." first_verify_time  > ".strtotime("-6 month");
		$smap[4] = $map." first_verify_time  > ".strtotime("-1 year");
		$smap[5] = $map." true " ;

		$smap[6] = $map;
		if(!empty($_REQUEST['start_time'])){
			$xtime = strtotime(urldecode($_REQUEST['start_time']));
			$smap[6] .= " first_verify_time >$xtime AND ";
			$search['start_time'] = $xtime;	
		}
		if(!empty($_REQUEST['end_time'])){
			$xtime = strtotime(urldecode($_REQUEST['end_time']));
			$smap[6] .= " first_verify_time <$xtime AND ";
			$search['end_time'] = $xtime;	
		}
		$smap[6] .= " true " ;

		foreach ($smap as $key => $val) {
			$data = M("borrow_info")->field("borrow_type,count(id) AS num,sum(borrow_money) AS money")->where($val)->group("borrow_type")->select();
			foreach ($data as $k => $v) {
				$list[$v['borrow_type']]['num'][$key] = $v['num'];
				$list[$v['borrow_type']]['money'][$key] = $v['money'];
				$list[6]['num'][$key] += $v['num'];
				$list[6]['money'][$key] += $v['money'];
			}
		}

		// var_dump($list);
        $this->assign("list", $list);

		$this->assign('type_list',$Bconfig['BORROW_STATUS']);
        $this->assign("query", http_build_query($search));
        $this->display();
	}
}
?>