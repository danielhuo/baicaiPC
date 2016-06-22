<?php
// 全局设置
class BillAction extends ACommonAction
{
    /**
    +----------------------------------------------------------
    * 默认操作
    +----------------------------------------------------------
    */
	public function downagreement(){
		$per = C('DB_PREFIX');
		//$borrow_config = require C("APP_ROOT")."Conf/borrow_config.php";
		$invest_id=intval($_GET['invest_id']);
		//$borrow_id=intval($_GET['id']);
		
		$iinfo = M('borrow_investor')->field('id,borrow_id,investor_capital,investor_interest,deadline,investor_uid,add_time')->where("id={$invest_id}")->find();
		$binfo = M('borrow_info')->field('id,repayment_type,borrow_duration,borrow_fee,borrow_uid,borrow_deposit,borrow_interest,borrow_type,borrow_use,borrow_money,full_time,add_time,borrow_interest_rate,deadline,second_verify_time')->find($iinfo['borrow_id']);
		$mBorrow = M("members m")->join("{$per}member_info mi ON mi.uid=m.id")->field('mi.real_name,mi.idcard,m.user_name')->where("m.id={$binfo['borrow_uid']}")->find();
		$mInvest = M("members m")->join("{$per}member_info mi ON mi.uid=m.id")->field('mi.real_name,mi.idcard,m.user_name')->where("m.id={$iinfo['investor_uid']}")->find();
		if(!is_array($iinfo)||!is_array($binfo)||!is_array($mBorrow)||!is_array($mInvest)) exit;
		$smoney=(int)$binfo['borrow_deposit']+(int)$binfo['borrow_money'];
		$textcordon=M('global')->field('text')->where("id=117")->find();//警戒线
		$textunwind=M('global')->field('text')->where("id=118")->find();//平仓线
		$ctext=(int)$textcordon['text'];
		$wtext=(int)$textunwind['text'];
		$money=(int)$binfo['borrow_money'];
		$cordon=$ctext*$money/100;
		$unwind=$wtext*$money/100;
		$this->assign('cordon', $cordon);
		$this->assign('unwind', $unwind);
		   
			$name=$mBorrow['real_name'];
			$this->assign('name', $name);
			$card_s=$mBorrow['idcard'];
			$this->assign('card_s', $card_s);
			
		$detail = M('investor_detail d')->field('d.borrow_id,d.investor_uid,d.borrow_uid,d.interest,d.capital,sum(d.capital+d.interest-d.interest_fee) benxi,d.total')->where("d.borrow_id={$iinfo['borrow_id']} and d.invest_id ={$iinfo['id']}")->group('d.investor_uid')->find();

		//$detailinfo = M('investor_detail d')->join("{$per}borrow_investor bi ON bi.id=d.invest_id")->join("{$per}members m ON m.id=d.investor_uid")->field('d.borrow_id,d.investor_uid,d.borrow_uid,d.capital,sum(d.capital+d.interest-d.interest_fee) benxi,d.total,m.user_name,bi.investor_capital,bi.add_time')->where("d.borrow_id={$iinfo['borrow_id']} and d.invest_id ={$iinfo['id']}")->group('d.investor_uid')->find();
		$detailinfo = M('investor_detail d')->field('d.borrow_id,d.investor_uid,d.borrow_uid,(d.capital+d.interest-d.interest_fee) benxi,d.capital,d.interest,d.interest_fee,d.sort_order,d.deadline')->where("d.borrow_id={$iinfo['borrow_id']} and d.invest_id ={$iinfo['id']}")->select();
		
		
		$time = M('borrow_investor')->field('id,add_time')->where("borrow_id={$iinfo['borrow_id']} order by add_time asc")->limit(1)->find();
		
		if($binfo['repayment_type']==1){
				$deadline_last = strtotime("+{$binfo['borrow_duration']} day",$time['add_time']);
			}else{
				$deadline_last = strtotime("+{$binfo['borrow_duration']} month",$time['add_time']);
			}
		$this->assign('deadline_last',$deadline_last);
		$this->assign('detailinfo',$detailinfo);
		$this->assign('detail',$detail);
        
		$type1 = $this->gloconf['BORROW_USE'];
		$binfo['borrow_use'] = $type1[$binfo['borrow_use']];
		$ht=M('hetong')->field('hetong_img,name,dizhi,tel')->find();
		
		$this->assign("ht",$ht);
		$type = $borrow_config['REPAYMENT_TYPE'];
		//echo $binfo['repayment_type'];
		$binfo['repayment_name'] = $type[$binfo['repayment_type']];

		$iinfo['repay'] = getFloatValue(($iinfo['investor_capital']+$iinfo['investor_interest'])/$binfo['borrow_duration'],2);
		$contract_number="bytp2pD".date("Ymd",$iinfo['add_time']).$invest_id;
		
		//print_r($type);
		$this->assign('iinfo',$iinfo);
		$this->assign('binfo',$binfo);
		$this->assign('mBorrow',$mBorrow);
		$this->assign('mInvest',$mInvest);
      
		$detail_list = M('investor_detail')->field(true)->where("invest_id={$invest_id}")->select();
		$this->assign("detail_list",$detail_list);
		
		//生成word
		import("ORG.Io.PHPWord");

		$PHPWord = new PHPWord();
       
		$document = $PHPWord->loadTemplate(C("APP_ROOT")."Conf/Member/agreement.docx");
		
        //合同编号
		
		$document->setValue('id', $contract_number);
		
		//生成时间
		$document->setValue('time', date("Y-m-d",$binfo['second_verify_time']));
		//投资人百财名
		$document->setValue('iname', iconv('utf-8', 'GB2312//IGNORE', $mInvest['user_name']));
		
		//$document->setValue('iname', $mInvest['user_name']);
		//出借金额
		$document->setValue('invest', $iinfo['investor_capital']);
		//借款期限
		$document->setValue('bduration', $binfo['borrow_duration']);
		//投资人利息
		$document->setValue('inter', $detail['interest']);
		//借款人真实姓名
		$document->setValue('realname', iconv('utf-8', 'GB2312//IGNORE', $name));
		//$document->setValue('realname', $name);
		//身份证号码
		$document->setValue('cardid', $card_s);
		//借款人百财名
		$document->setValue('bname', iconv('utf-8', 'GB2312//IGNORE', $mBorrow['user_name']));
		//$document->setValue('bname', $mBorrow['user_name']);
		//项目名称
		$document->setValue('bid', $binfo['id']);
		//保证金
		$document->setValue('bao', $binfo['borrow_deposit']);
		//初始金额
		$document->setValue('money', $smoney);
		//管理费
		$document->setValue('fee', $binfo['borrow_fee']);
		//借款期限
		$document->setValue('dtime',date("Y-m-d",$binfo['deadline']));
		//警戒线
		$document->setValue('jing', $cordon);
		//平仓线
		$document->setValue('unwind', $unwind);
		
		
		header( "Content-type:   application/octet-stream ");
        header( "Accept-Ranges:   bytes ");
        header( "Content-Disposition:   attachment;   filename= {$contract_number}.docx"); 
		header("Pragma:no-cache"); 
        header("Expires:0"); 
		//echo file_get_contents(C("APP_ROOT")."Conf/Member/test.docx");
		 
       // readfile(C("APP_ROOT")."Conf/Member/test.docx");
		$filename=$contract_number.".docx";
		$document->save($filename);
		readfile($filename);
		if(file_exists($filename)) {
            unlink($filename);
        }
    }
	
    public function waitverify()
    {
		$map=array();
		$map['b.status'] = 0;
		if(!empty($_REQUEST['uname'])&&!$_REQUEST['uid'] || $_REQUEST['uname']!=$_REQUEST['olduname']){
			$uid = M("members")->getFieldByUserName(text($_REQUEST['uname']),'id');
			$map['b.uid'] = $uid;
			$search['uid'] = $map['b.uid'];
			$search['uname'] = $_REQUEST['uname'];
		}
		if( !empty($_REQUEST['uid'])&&!isset($search['uname']) ){
			$map['b.uid'] = intval($_REQUEST['uid']);
			$search['uid'] = $map['b.uid'];
			$search['uname'] = $_REQUEST['uname'];
		}

		if(!empty($_REQUEST['bj']) && !empty($_REQUEST['money'])){
			$map['b.amount'] = array($_REQUEST['bj'],$_REQUEST['money']);
			$search['bj'] = $_REQUEST['bj'];	
			$search['money'] = $_REQUEST['money'];	
		}

		if(!empty($_REQUEST['start_time']) && !empty($_REQUEST['end_time'])){
			$timespan = strtotime(urldecode($_REQUEST['start_time'])).",".strtotime(urldecode($_REQUEST['end_time']));
			$map['b.birthtime'] = array("between",$timespan);
			$search['start_time'] = urldecode($_REQUEST['start_time']);	
			$search['end_time'] = urldecode($_REQUEST['end_time']);	
		}elseif(!empty($_REQUEST['start_time'])){
			$xtime = strtotime(urldecode($_REQUEST['start_time']));
			$map['b.birthtime'] = array("gt",$xtime);
			$search['start_time'] = $xtime;	
		}elseif(!empty($_REQUEST['end_time'])){
			$xtime = strtotime(urldecode($_REQUEST['end_time']));
			$map['b.birthtime'] = array("lt",$xtime);
			$search['end_time'] = $xtime;	
		}
		
		//if(session('admin_is_kf')==1){
		//		$map['m.customer_id'] = session('admin_id');
		//}else{
			if($_REQUEST['customer_id'] && $_REQUEST['customer_name']){
				$map['m.customer_id'] = $_REQUEST['customer_id'];
				$search['customer_id'] = $map['m.customer_id'];	
				$search['customer_name'] = urldecode($_REQUEST['customer_name']);	
			}
			
			if($_REQUEST['customer_name'] && !$search['customer_id']){
				$cusname = urldecode($_REQUEST['customer_name']);
				$kfid = M('ausers')->getFieldByUserName($cusname,'id');
				$map['m.customer_id'] = $kfid;
				$search['customer_name'] = $cusname;	
				$search['customer_id'] = $kfid;	
			}
		//}
		//分页处理
		import("ORG.Util.Page");
		$count = M('bill b')->join("{$this->pre}members m ON m.id=b.uid")->where($map)->count('b.id');
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理
		
		$field= 'b.id,b.name,b.uid,b.duration,b.amount,b.interest_rate,b.deadline,b.collect_day,m.user_name,m.id mid';
		$list = M('bill b')->field($field)->join("{$this->pre}members m ON m.id=b.uid")->where($map)->limit($Lsql)->order("b.id DESC")->select();
		
		$list = $this->_listFilter($list);
		
        $this->assign("bj", array("gt"=>'大于',"eq"=>'等于',"lt"=>'小于'));
        $this->assign("list", $list);
        $this->assign("pagebar", $page);
        $this->assign("search", $search);
		$this->assign("xaction",ACTION_NAME);
        $this->assign("query", http_build_query($search));
		
        $this->display();
    }
	
    public function waitverify2()
    {
		$map=array();
		$map['b.status'] = 4;
		if(!empty($_REQUEST['uname'])&&!$_REQUEST['uid'] || $_REQUEST['uname']!=$_REQUEST['olduname']){
			$uid = M("members")->getFieldByUserName(text($_REQUEST['uname']),'id');
			$map['b.borrow_uid'] = $uid;
			$search['uid'] = $map['b.borrow_uid'];
			$search['uname'] = $_REQUEST['uname'];
		}
		if( !empty($_REQUEST['uid'])&&!isset($search['uname']) ){
			$map['b.borrow_uid'] = intval($_REQUEST['uid']);
			$search['uid'] = $map['b.borrow_uid'];
			$search['uname'] = $_REQUEST['uname'];
		}

		if(!empty($_REQUEST['bj']) && !empty($_REQUEST['money'])){
			$map['b.borrow_money'] = array($_REQUEST['bj'],$_REQUEST['money']);
			$search['bj'] = $_REQUEST['bj'];	
			$search['money'] = $_REQUEST['money'];	
		}

		if(!empty($_REQUEST['start_time']) && !empty($_REQUEST['end_time'])){
			$timespan = strtotime(urldecode($_REQUEST['start_time'])).",".strtotime(urldecode($_REQUEST['end_time']));
			$map['b.add_time'] = array("between",$timespan);
			$search['start_time'] = urldecode($_REQUEST['start_time']);	
			$search['end_time'] = urldecode($_REQUEST['end_time']);	
		}elseif(!empty($_REQUEST['start_time'])){
			$xtime = strtotime(urldecode($_REQUEST['start_time']));
			$map['b.add_time'] = array("gt",$xtime);
			$search['start_time'] = $xtime;	
		}elseif(!empty($_REQUEST['end_time'])){
			$xtime = strtotime(urldecode($_REQUEST['end_time']));
			$map['b.add_time'] = array("lt",$xtime);
			$search['end_time'] = $xtime;	
		}
		
		//if(session('admin_is_kf')==1){
		//		$map['m.customer_id'] = session('admin_id');
		//}else{
			if($_REQUEST['customer_id'] && $_REQUEST['customer_name']){
				$map['m.customer_id'] = $_REQUEST['customer_id'];
				$search['customer_id'] = $map['m.customer_id'];	
				$search['customer_name'] = urldecode($_REQUEST['customer_name']);	
			}
			
			if($_REQUEST['customer_name'] && !$search['customer_id']){
				$cusname = urldecode($_REQUEST['customer_name']);
				$kfid = M('ausers')->getFieldByUserName($cusname,'id');
				$map['m.customer_id'] = $kfid;
				$search['customer_name'] = $cusname;	
				$search['customer_id'] = $kfid;	
			}
		//}
		//分页处理
		import("ORG.Util.Page");
		$count = M('bill b')->join("{$this->pre}members m ON m.id=b.uid")->where($map)->count('b.id');
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理
		
		$list = M('bill b')->field("b.*,m.user_name")->join("{$this->pre}members m ON m.id=b.uid")->where($map)->limit($Lsql)->order("b.id DESC")->select();
		$list = $this->_listFilter($list);
		
        $this->assign("bj", array("gt"=>'大于',"eq"=>'等于',"lt"=>'小于'));
        $this->assign("list", $list);
		$this->assign("name", '票据抵押借款');
        $this->assign("pagebar", $page);
        $this->assign("search", $search);
		$this->assign("xaction",ACTION_NAME);
        $this->assign("query", http_build_query($search));
		
        $this->display();
    }
	
    public function waitmoney()
    {
		$map=array();
		$map['b.status'] = 2;
		if(!empty($_REQUEST['uname'])&&!$_REQUEST['uid'] || $_REQUEST['uname']!=$_REQUEST['olduname']){
			$uid = M("members")->getFieldByUserName(text($_REQUEST['uname']),'id');
			$map['b.uid'] = $uid;
			$search['uid'] = $map['b.uid'];
			$search['uname'] = $_REQUEST['uname'];
		}
		if( !empty($_REQUEST['uid'])&&!isset($search['uname']) ){
			$map['b.uid'] = intval($_REQUEST['uid']);
			$search['uid'] = $map['b.uid'];
			$search['uname'] = $_REQUEST['uname'];
		}

		if(!empty($_REQUEST['bj']) && !empty($_REQUEST['money'])){
			$map['b.amount'] = array($_REQUEST['bj'],$_REQUEST['money']);
			$search['bj'] = $_REQUEST['bj'];	
			$search['money'] = $_REQUEST['money'];	
		}

		if(!empty($_REQUEST['start_time']) && !empty($_REQUEST['end_time'])){
			$timespan = strtotime(urldecode($_REQUEST['start_time'])).",".strtotime(urldecode($_REQUEST['end_time']));
			$map['b.add_time'] = array("between",$timespan);
			$search['start_time'] = urldecode($_REQUEST['start_time']);	
			$search['end_time'] = urldecode($_REQUEST['end_time']);	
		}elseif(!empty($_REQUEST['start_time'])){
			$xtime = strtotime(urldecode($_REQUEST['start_time']));
			$map['b.add_time'] = array("gt",$xtime);
			$search['start_time'] = $xtime;	
		}elseif(!empty($_REQUEST['end_time'])){
			$xtime = strtotime(urldecode($_REQUEST['end_time']));
			$map['b.add_time'] = array("lt",$xtime);
			$search['end_time'] = $xtime;	
		}
		
		//if(session('admin_is_kf')==1){
		//		$map['m.customer_id'] = session('admin_id');
		//}else{
			if($_REQUEST['customer_id'] && $_REQUEST['customer_name']){
				$map['m.customer_id'] = $_REQUEST['customer_id'];
				$search['customer_id'] = $map['m.customer_id'];	
				$search['customer_name'] = urldecode($_REQUEST['customer_name']);	
			}
			
			if($_REQUEST['customer_name'] && !$search['customer_id']){
				$cusname = urldecode($_REQUEST['customer_name']);
				$kfid = M('ausers')->getFieldByUserName($cusname,'id');
				$map['m.customer_id'] = $kfid;
				$search['customer_name'] = $cusname;	
				$search['customer_id'] = $kfid;	
			}
		//}
		//分页处理
		import("ORG.Util.Page");
		$count = M('bill b')->join("{$this->pre}members m ON m.id=b.uid")->where($map)->count('b.id');
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理

		$field= 'b.*,m.user_name';
		$list = M('bill b')->field($field)->join("{$this->pre}members m ON m.id=b.uid")->where($map)->limit($Lsql)->order("b.id DESC")->select();
		$list = $this->_listFilter($list);
		foreach($list as $key=>$v){
		    $list[$key]['progress'] = getFloatValue($v['has_borrow']/$v['amount']*100,2);
		}
        $this->assign("bj", array("gt"=>'大于',"eq"=>'等于',"lt"=>'小于'));
        $this->assign("list", $list);
        $this->assign("pagebar", $page);
        $this->assign("search", $search);
		$this->assign("xaction",ACTION_NAME);
        $this->assign("query", http_build_query($search));
		
        $this->display();
    }
	public function transferring()
    {
		$map=array();
		$map['b.status'] = array('in',array(6));//划款中
		if(!empty($_REQUEST['uname'])&&!$_REQUEST['uid'] || $_REQUEST['uname']!=$_REQUEST['olduname']){
			$uid = M("members")->getFieldByUserName(text($_REQUEST['uname']),'id');
			$map['b.uid'] = $uid;
			$search['uid'] = $map['b.uid'];
			$search['uname'] = $_REQUEST['uname'];
		}
		if( !empty($_REQUEST['uid'])&&!isset($search['uname']) ){
			$map['b.uid'] = intval($_REQUEST['uid']);
			$search['uid'] = $map['b.uid'];
			$search['uname'] = $_REQUEST['uname'];
		}

		if(!empty($_REQUEST['bj']) && !empty($_REQUEST['money'])){
			$map['b.amount'] = array($_REQUEST['bj'],$_REQUEST['money']);
			$search['bj'] = $_REQUEST['bj'];	
			$search['money'] = $_REQUEST['money'];	
		}

		if(!empty($_REQUEST['start_time']) && !empty($_REQUEST['end_time'])){
			$timespan = strtotime(urldecode($_REQUEST['start_time'])).",".strtotime(urldecode($_REQUEST['end_time']));
			$map['b.add_time'] = array("between",$timespan);
			$search['start_time'] = urldecode($_REQUEST['start_time']);	
			$search['end_time'] = urldecode($_REQUEST['end_time']);	
		}elseif(!empty($_REQUEST['start_time'])){
			$xtime = strtotime(urldecode($_REQUEST['start_time']));
			$map['b.add_time'] = array("gt",$xtime);
			$search['start_time'] = $xtime;	
		}elseif(!empty($_REQUEST['end_time'])){
			$xtime = strtotime(urldecode($_REQUEST['end_time']));
			$map['b.add_time'] = array("lt",$xtime);
			$search['end_time'] = $xtime;	
		}
		
		
			if($_REQUEST['customer_id'] && $_REQUEST['customer_name']){
				$map['m.customer_id'] = $_REQUEST['customer_id'];
				$search['customer_id'] = $map['m.customer_id'];	
				$search['customer_name'] = urldecode($_REQUEST['customer_name']);	
			}
			
			if($_REQUEST['customer_name'] && !$search['customer_id']){
				$cusname = urldecode($_REQUEST['customer_name']);
				$kfid = M('ausers')->getFieldByUserName($cusname,'id');
				$map['m.customer_id'] = $kfid;
				$search['customer_name'] = $cusname;	
				$search['customer_id'] = $kfid;	
			}
		//}
		//分页处理
		import("ORG.Util.Page");
		$count = M('bill b')->join("{$this->pre}members m ON m.id=b.uid")->where($map)->count('b.id');
		
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理

		$field= 'm.id as mid,b.id,b.name,b.uid,b.duration,b.amount,b.status,b.interest_rate,b.deadline,m.user_name,m.user_phone,b.has_borrow';
		$list = M('bill b')->field($field)->join("{$this->pre}members m ON m.id=b.uid")->where($map)->limit($Lsql)->order("b.id DESC")->select();
		
		$list = $this->_listFilter($list);
		
        $this->assign("bj", array("gt"=>'大于',"eq"=>'等于',"lt"=>'小于'));
        $this->assign("list", $list);
        $this->assign("pagebar", $page);
        $this->assign("search", $search);
		$this->assign("xaction",ACTION_NAME);
        $this->assign("query", http_build_query($search));
		
        $this->display();
    }
	
    public function repaymenting()
    {
		$model = D(ucfirst($this->getActionName()));
		$field ='*';
		$map['status'] = array('in',array(7));
		$this->_list($model,$field,$map);
		
		$this->display();
    }
	
    
	
	public function unfinish(){
		$map=array();
		$map['b.borrow_status'] = 3;
		if(!empty($_REQUEST['uname'])&&!$_REQUEST['uid'] || $_REQUEST['uname']!=$_REQUEST['olduname']){
			$uid = M("members")->getFieldByUserName(text($_REQUEST['uname']),'id');
			$map['b.borrow_uid'] = $uid;
			$search['uid'] = $map['b.borrow_uid'];
			$search['uname'] = $_REQUEST['uname'];
		}
		if( !empty($_REQUEST['uid'])&&!isset($search['uname']) ){
			$map['b.borrow_uid'] = intval($_REQUEST['uid']);
			$search['uid'] = $map['b.borrow_uid'];
			$search['uname'] = $_REQUEST['uname'];
		}

		if(!empty($_REQUEST['bj']) && !empty($_REQUEST['money'])){
			$map['b.borrow_money'] = array($_REQUEST['bj'],$_REQUEST['money']);
			$search['bj'] = $_REQUEST['bj'];	
			$search['money'] = $_REQUEST['money'];	
		}

		if(!empty($_REQUEST['start_time']) && !empty($_REQUEST['end_time'])){
			$timespan = strtotime(urldecode($_REQUEST['start_time'])).",".strtotime(urldecode($_REQUEST['end_time']));
			$map['b.add_time'] = array("between",$timespan);
			$search['start_time'] = urldecode($_REQUEST['start_time']);	
			$search['end_time'] = urldecode($_REQUEST['end_time']);	
		}elseif(!empty($_REQUEST['start_time'])){
			$xtime = strtotime(urldecode($_REQUEST['start_time']));
			$map['b.add_time'] = array("gt",$xtime);
			$search['start_time'] = $xtime;	
		}elseif(!empty($_REQUEST['end_time'])){
			$xtime = strtotime(urldecode($_REQUEST['end_time']));
			$map['b.add_time'] = array("lt",$xtime);
			$search['end_time'] = $xtime;	
		}
		
		//if(session('admin_is_kf')==1){
		//		$map['m.customer_id'] = session('admin_id');
		//}else{
			if($_REQUEST['customer_id'] && $_REQUEST['customer_name']){
				$map['m.customer_id'] = $_REQUEST['customer_id'];
				$search['customer_id'] = $map['m.customer_id'];	
				$search['customer_name'] = urldecode($_REQUEST['customer_name']);	
			}
			
			if($_REQUEST['customer_name'] && !$search['customer_id']){
				$cusname = urldecode($_REQUEST['customer_name']);
				$kfid = M('ausers')->getFieldByUserName($cusname,'id');
				$map['m.customer_id'] = $kfid;
				$search['customer_name'] = $cusname;	
				$search['customer_id'] = $kfid;	
			}
		//}
		//分页处理
		import("ORG.Util.Page");
		$count = M('borrow_info b')->join("{$this->pre}members m ON m.id=b.borrow_uid")->where($map)->count('b.id');
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理

		$field= 'b.id,b.borrow_name,b.borrow_status,b.borrow_uid,b.borrow_duration,b.borrow_type,b.borrow_money,b.updata,b.borrow_fee,b.borrow_interest_rate,b.repayment_type,b.deadline,m.id mid,m.user_name,v.deal_user_2,v.deal_time_2,v.deal_info_2';
		$list = M('borrow_info b')->field($field)->join("{$this->pre}members m ON m.id=b.borrow_uid")->join("{$this->pre}borrow_verify v ON b.id=v.borrow_id")->where($map)->limit($Lsql)->order("b.id DESC")->select();
		$list = $this->_listFilter($list);
		
        $this->assign("bj", array("gt"=>'大于',"eq"=>'等于',"lt"=>'小于'));
        $this->assign("list", $list);
        $this->assign("pagebar", $page);
        $this->assign("search", $search);
		$this->assign("xaction",ACTION_NAME);
        $this->assign("query", http_build_query($search));
		
        $this->display();
	}

    public function fail()
    {
		$map=array();
		$map['b.borrow_status'] = 1;
		if(!empty($_REQUEST['uname'])&&!$_REQUEST['uid'] || $_REQUEST['uname']!=$_REQUEST['olduname']){
			$uid = M("members")->getFieldByUserName(text($_REQUEST['uname']),'id');
			$map['b.borrow_uid'] = $uid;
			$search['uid'] = $map['b.borrow_uid'];
			$search['uname'] = $_REQUEST['uname'];
		}
		if( !empty($_REQUEST['uid'])&&!isset($search['uname']) ){
			$map['b.borrow_uid'] = intval($_REQUEST['uid']);
			$search['uid'] = $map['b.borrow_uid'];
			$search['uname'] = $_REQUEST['uname'];
		}

		if(!empty($_REQUEST['bj']) && !empty($_REQUEST['money'])){
			$map['b.borrow_money'] = array($_REQUEST['bj'],$_REQUEST['money']);
			$search['bj'] = $_REQUEST['bj'];	
			$search['money'] = $_REQUEST['money'];	
		}

		if(!empty($_REQUEST['start_time']) && !empty($_REQUEST['end_time'])){
			$timespan = strtotime(urldecode($_REQUEST['start_time'])).",".strtotime(urldecode($_REQUEST['end_time']));
			$map['b.add_time'] = array("between",$timespan);
			$search['start_time'] = urldecode($_REQUEST['start_time']);	
			$search['end_time'] = urldecode($_REQUEST['end_time']);	
		}elseif(!empty($_REQUEST['start_time'])){
			$xtime = strtotime(urldecode($_REQUEST['start_time']));
			$map['b.add_time'] = array("gt",$xtime);
			$search['start_time'] = $xtime;	
		}elseif(!empty($_REQUEST['end_time'])){
			$xtime = strtotime(urldecode($_REQUEST['end_time']));
			$map['b.add_time'] = array("lt",$xtime);
			$search['end_time'] = $xtime;	
		}
		
		//if(session('admin_is_kf')==1){
		//		$map['m.customer_id'] = session('admin_id');
		//}else{
			if($_REQUEST['customer_id'] && $_REQUEST['customer_name']){
				$map['m.customer_id'] = $_REQUEST['customer_id'];
				$search['customer_id'] = $map['m.customer_id'];	
				$search['customer_name'] = urldecode($_REQUEST['customer_name']);	
			}
			
			if($_REQUEST['customer_name'] && !$search['customer_id']){
				$cusname = urldecode($_REQUEST['customer_name']);
				$kfid = M('ausers')->getFieldByUserName($cusname,'id');
				$map['m.customer_id'] = $kfid;
				$search['customer_name'] = $cusname;	
				$search['customer_id'] = $kfid;	
			}
		//}
		//分页处理
		import("ORG.Util.Page");
		$count = M('borrow_info b')->join("{$this->pre}members m ON m.id=b.borrow_uid")->where($map)->count('b.id');
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理

		$field= 'b.id,b.borrow_name,b.borrow_status,b.borrow_uid,b.borrow_duration,b.borrow_type,b.borrow_money,b.updata,b.borrow_fee,b.borrow_interest_rate,b.repayment_type,b.add_time,m.user_name,v.deal_user,v.deal_time,m.id mid,v.deal_info';
		$list = M('borrow_info b')->field($field)->join("{$this->pre}members m ON m.id=b.borrow_uid")->join("{$this->pre}borrow_verify v ON b.id=v.borrow_id")->where($map)->limit($Lsql)->order("b.id DESC")->select();
		$list = $this->_listFilter($list);
		
        $this->assign("bj", array("gt"=>'大于',"eq"=>'等于',"lt"=>'小于'));
        $this->assign("list", $list);
        $this->assign("pagebar", $page);
        $this->assign("search", $search);
		$this->assign("xaction",ACTION_NAME);
        $this->assign("query", http_build_query($search));
		
        $this->display();
    }
	
    public function fail2()
    {
		$map=array();
		$map['b.borrow_status'] = 5;
		if(!empty($_REQUEST['uname'])&&!$_REQUEST['uid'] || $_REQUEST['uname']!=$_REQUEST['olduname']){
			$uid = M("members")->getFieldByUserName(text($_REQUEST['uname']),'id');
			$map['b.borrow_uid'] = $uid;
			$search['uid'] = $map['b.borrow_uid'];
			$search['uname'] = $_REQUEST['uname'];
		}
		if( !empty($_REQUEST['uid'])&&!isset($search['uname']) ){
			$map['b.borrow_uid'] = intval($_REQUEST['uid']);
			$search['uid'] = $map['b.borrow_uid'];
			$search['uname'] = $_REQUEST['uname'];
		}

		if(!empty($_REQUEST['bj']) && !empty($_REQUEST['money'])){
			$map['b.borrow_money'] = array($_REQUEST['bj'],$_REQUEST['money']);
			$search['bj'] = $_REQUEST['bj'];	
			$search['money'] = $_REQUEST['money'];	
		}

		if(!empty($_REQUEST['start_time']) && !empty($_REQUEST['end_time'])){
			$timespan = strtotime(urldecode($_REQUEST['start_time'])).",".strtotime(urldecode($_REQUEST['end_time']));
			$map['b.add_time'] = array("between",$timespan);
			$search['start_time'] = urldecode($_REQUEST['start_time']);	
			$search['end_time'] = urldecode($_REQUEST['end_time']);	
		}elseif(!empty($_REQUEST['start_time'])){
			$xtime = strtotime(urldecode($_REQUEST['start_time']));
			$map['b.add_time'] = array("gt",$xtime);
			$search['start_time'] = $xtime;	
		}elseif(!empty($_REQUEST['end_time'])){
			$xtime = strtotime(urldecode($_REQUEST['end_time']));
			$map['b.add_time'] = array("lt",$xtime);
			$search['end_time'] = $xtime;	
		}
		
		//if(session('admin_is_kf')==1){
		//		$map['m.customer_id'] = session('admin_id');
		//}else{
			if($_REQUEST['customer_id'] && $_REQUEST['customer_name']){
				$map['m.customer_id'] = $_REQUEST['customer_id'];
				$search['customer_id'] = $map['m.customer_id'];	
				$search['customer_name'] = urldecode($_REQUEST['customer_name']);	
			}
			
			if($_REQUEST['customer_name'] && !$search['customer_id']){
				$cusname = urldecode($_REQUEST['customer_name']);
				$kfid = M('ausers')->getFieldByUserName($cusname,'id');
				$map['m.customer_id'] = $kfid;
				$search['customer_name'] = $cusname;	
				$search['customer_id'] = $kfid;	
			}
		//}
		//分页处理
		import("ORG.Util.Page");
		$count = M('borrow_info b')->join("{$this->pre}members m ON m.id=b.borrow_uid")->where($map)->count('b.id');
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理

		$field= 'b.id,b.borrow_name,b.borrow_status,b.borrow_uid,b.borrow_duration,b.borrow_type,b.borrow_money,b.updata,b.borrow_fee,b.borrow_interest_rate,b.repayment_type,b.add_time,m.user_name,m.id mid,v.deal_user_2,v.deal_time_2,v.deal_info_2';
		$list = M('borrow_info b')->field($field)->join("{$this->pre}members m ON m.id=b.borrow_uid")->join("{$this->pre}borrow_verify v ON b.id=v.borrow_id")->where($map)->limit($Lsql)->order("b.id DESC")->select();
		$list = $this->_listFilter($list);
		
        $this->assign("bj", array("gt"=>'大于',"eq"=>'等于',"lt"=>'小于'));
        $this->assign("list", $list);
        $this->assign("pagebar", $page);
        $this->assign("search", $search);
		$this->assign("xaction",ACTION_NAME);
        $this->assign("query", http_build_query($search));
		
        $this->display();
    }
	
    public function _addFilter()
    {
		$typelist = get_type_leve_list('0','acategory');//分级栏目
		$this->assign('type_list',$typelist);
		
    }
	
    public function _editFilter($id)
    {
		$Bconfig = require C("APP_ROOT")."Conf/borrow_config.php";
		$borrow_status = $Bconfig['BORROW_STATUS'];
	 	//$BType = $Bconfig['BORROW_TYPE'];
		switch(strtolower(session('listaction'))){
			case "waitverify":
				for($i=0;$i<=10;$i++){
					if(in_array($i,array("1","2")) ) continue;
					unset($borrow_status[$i]);
				}
			break;
			case "waitverify2":
				for($i=0;$i<=10;$i++){
					if(in_array($i,array("5","6")) ) continue;
					unset($borrow_status[$i]);
				}
			break;
			case "waitmoney":
				for($i=0;$i<=10;$i++){
					if(in_array($i,array("2","3")) ) continue;
					unset($borrow_status[$i]);
				}
			break;
			case "repaymenting":
				for($i=0;$i<=10;$i++){
					if(in_array($i,array("6")) ) continue;
					unset($borrow_status[$i]);
				}
			break;
			case "fail":
				unset($borrow_status['3'],$borrow_status['4'],$borrow_status['5']);
			break;
		}
		///////////////////////////////////////////////////////////////////////////////////
		//$danbao = M('article_category')->field('id,type_name')->where("type_name='合作机构资质展示'")->select();
		
		//$sql = M('article')->field("id,title")->where("type_id =7")->select();//"select id,title from lzh_article where type_id =7";
		$danbao = M('article')->field("id,title")->where("type_id =7")->select();//M()->query($sql);
		$dblist = array();
		if(is_array($danbao)){
			foreach($danbao as $key => $v){
				$dblist[$v['id']]=$v['title'];
			}
		}
		$this->assign("danbao_list",$dblist);//新增担保标A+
		//////////////////////////////////////////////////////////////////////////////
		$this->assign('xact',session('listaction'));
		$btype = $Bconfig['REPAYMENT_TYPE'];
		$this->assign("vv",M("borrow_verify")->find($id));
		$this->assign('borrow_status',$borrow_status);
		$this->assign('type_list',$btype);
		$this->assign('borrow_type',$Bconfig['BORROW_TYPE']);
		//setBackUrl(session('listaction'));	
    }
	public function sRepayment(){
		$borrow_id = $_GET['id'];
		$binfo = M("borrow_info")->field("has_pay,total")->find($borrow_id);
		$from = $binfo['has_pay'] + 1;
		for($i=$from;$i<=$binfo['total'];$i++){
			$res = borrowRepayment($borrow_id,$i,2);
		}
		if($res===true){
			alogs("Repay",0,1,'网站代还成功！');//管理员操作日志
			$this->success("代还成功");
		}elseif(!empty($res)){
			$this->error($res);
		}else{
			alogs("Repay",0,0,'网站代还出错！');//管理员操作日志
			$this->error("代还出错，请重试");
		}
	}

	public function _doAddFilter($m){
		if(!empty($_FILES['imgfile']['name'])){
			$this->saveRule = date("YmdHis",time()).rand(0,1000);
			$this->savePathNew = C('ADMIN_UPLOAD_DIR').'Article/' ;
			$this->thumbMaxWidth = C('ARTICLE_UPLOAD_W');
			$this->thumbMaxHeight = C('ARTICLE_UPLOAD_H');
			$info = $this->CUpload();
			$data['art_img'] = $info[0]['savepath'].$info[0]['savename'];
		}
		if($data['art_img']) $m->art_img=$data['art_img'];
		$m->art_time=time();
		if($_POST['is_remote']==1) $m->art_content = get_remote_img($m->art_content);
		return $m;
	}

	public function doEditWaitverify(){
		$id=intval($_POST['id']);
        $status=intval($_POST['status']);
		$collect_day=intval($_POST['collect_day']);
		$deal_info=$_POST['deal_info'];
		
		$bill=M('Bill')->find($id);
		if($status==2){ 
		    $bill['collect_day']= $collect_day;
			$bill['collect_time']= strtotime("+ {$collect_day} days");
		}elseif($status!=1){
			$this->error(L('请选择是否通过！'));
		}
		$bill['status']=$status;
        $bill['firstview'] = time();
		
		$result=M('bill')->save($bill);
		
        if($result) { 
			$verify_info['bill_id'] = $id;
			$verify_info['deal_info'] = text($deal_info);
			$verify_info['deal_user'] = $this->admin_id;
			$verify_info['deal_time'] = time();
			$verify_info['deal_status'] = $status;
			
			if(M("bill_verify")->find($id))
				M('bill_verify')->save($verify_info);
			else M('bill_verify')->add($verify_info);
			alogs("doEditWait",$result,1,'初审操作成功！');//管理员操作日志
            //成功提示
            $this->assign('jumpUrl', __URL__."/".session('listaction'));
            $this->success(L('修改成功'));
        } else {
			alogs("doEditWait",$result,0,'初审操作失败！');//管理员操作日志
            //失败提示
            $this->error(L('修改失败'));
		}	
	}

	public function doEditWaitverify2(){
		$id=intval($_POST['id']);
		$status=intval($_POST['status']);
        $vm = M('bill')->find($id);
		$vo = M('bill_invest')->where("bill_id = {$id}")->select();
		
		if($status != 5 && $status != 6){
			$this->error(L('请选择是否通过！'));
		}
		$vm['status'] = $status;
		$vm['review'] = time();
		$result = M('bill')->save($vm);
		if($result!==false){
			$verify_info['deal_info2'] = $_POST['deal_info_2'];
			$verify_info['deal_user2'] = $this->admin_id;
			$verify_info['deal_time2'] = time();
			$verify_info['deal_status2'] = $status;
			M('bill_verify')->where("bill_id = {$id}")->save($verify_info);
			alogs("doEditWait2",$result,1,'复审操作成功！');//管理员操作日志
            //成功提示
            $this->assign('jumpUrl', __URL__."/".session('listaction'));
            $this->success(L('修改成功'));
		}else {
			alogs("doEditWait2",$result,0,'复审操作失败！');//管理员操作日志
            //失败提示
            $this->error(L('修改失败'));
		}	
        
	}


	public function doEditFail(){
        $m = D(ucfirst($this->getActionName()));
        if (false === $m->create()) {
            $this->error($m->getError());
        }
		$vm = M('borrow_info')->field('borrow_uid,borrow_status')->find($m->id);
		if($vm['borrow_status']==2 && $m->borrow_status<>2){
			$this->error('已通过审核的借款不能改为别的状态');
			exit;
		}
		
		foreach($_POST['updata_name'] as $key=>$v){
			$updata[$key]['name'] = $v;
			$updata[$key]['time'] = $_POST['updata_time'][$key];
		}
		$m->borrow_interest = getBorrowInterest($m->repayment_type,$m->borrow_money,$m->borrow_duration,$m->borrow_interest_rate);
		$m->updata = serialize($updata);
		$m->collect_time = strtotime($m->collect_time);
        //保存当前数据对象
        if ($result = $m->save()) { //保存成功
            //成功提示
            $this->assign('jumpUrl', __URL__."/".session('listaction'));
            $this->success(L('修改成功'));
        } else {
            //失败提示
            $this->error(L('修改失败'));
		}	
	}
	
		
			
	public function _AfterDoEdit(){
		switch(strtolower(session('listaction'))){
			case "waitverify":
				$v = M('borrow_info')->field('borrow_uid,borrow_status,deal_time')->find(intval($_POST['id']));
				if(empty($v['deal_time'])){
					$newid = M('members')->where("id={$v['borrow_uid']}")->setInc('credit_use',floatval($_POST['borrow_money']));
					if($newid) M('borrow_info')->where("id={$v['borrow_uid']}")->setField('deal_time',time());
				}
			break;
		}
	}
	
	public function _listFilter($list){
		session('listaction',ACTION_NAME);
		$row=array();
		$aUser = get_admin_name();
		foreach($list as $key=>$v){
			$user=M('members')->join('')->find($v['uid']);
			$v['user_name']=$user['user_name'];
			$v['user_phone']=$user['user_phone'];
			$row[$key]=$v;
		}
		return $row;
	}
	
	
	//每个借款标的投资人记录
	 public function doinvest()
    {
		$id = intval($_REQUEST['id']);
		$map=array();
		
		$map['bi.bill_id'] = $id;
		//分页处理
		import("ORG.Util.Page");
		$count = M('bill_invest bi')->join("{$this->pre}members m ON m.id=bi.invest_uid")->where($map)->count('bi.id');
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理

		$list = M('bill_invest bi')->join("{$this->pre}members m ON m.id=bi.invest_uid")->join("{$this->pre}bill b ON b.id=bi.bill_id")->where($map)->limit($Lsql)->order("bi.id DESC")->select();
		$list = $this->_listFilter($list);
		
		//dump($list);exit;
		$this->assign("name", '票据抵押借款');
        $this->assign("list", $list);
        $this->assign("pagebar", $page);
        $this->display();
    }
	
	
	public function huakuan(){
		//标号
		$id=intval($_GET['id']);
		
		//借款信息
		$borrower=M('Bill b')->join("lzh_members m ON b.uid=m.id")->field("b.id,m.id as mid,m.user_name,b.amount")->where("b.id =".$id)->find();
		
		//投资信息
		$investors = M('Bill_invest bi')->join("lzh_members m ON bi.invest_uid=m.id")->field("m.id,m.user_name,bi.invest_amount")->where("bi.bill_id =".$id)->select();
		
		$this->assign("borrower",$borrower);
		$this->assign("investors",$investors);
		$this->display();
	}
	
	public function dohuakuan(){
		//标号
		$id=intval($_GET['id']);
		
		//借款信息
		$bill=M('Bill')->find($id);
		//投资信息
		$invests= M('Bill_invest')->where("bill_id={$id}")->select();
		
		//融资入账
		$dealBill=memberMoneyLog($bill["uid"],17,$bill["amount"],"第{$id}{$bill['name']}融资成功");
		if(!$dealBill) return false;
		//投资出冻结,入待收
		for($i=0;$i<count($invests);$i++){
			$dealInvest= memberMoneyLog($invests[$i]["invest_uid"],15,$invests[$i]["invest_amount"],"第{$id}{$bill['name']}投资成功,投资出账");
			$dealInterest= memberMoneyLog($invests[$i]["invest_uid"],28,$invests[$i]["invest_interest"],"第{$id}{$bill['name']}投资成功,计息待收");
            if(!$dealInvest||!$dealInterest) return false;			
		}
		
	   
	    $bill['status']=7;
	    $save=M('Bill')->save($bill);            
		if(!$save)$this->error("划款失败");
		$this->success("划款成功","transferring");
	}
	public function doweek()
    {
		$map=array();
		$map['b.status'] =7;
		$map['b.deadline'] =array("elt",strtotime(date("Y-m-d"))+86400*7);
		
		//分页处理
		import("ORG.Util.Page");
		$borrow = M('bill b');
	
		$count = $borrow->where($map)->count('DISTINCT b.id');
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理
		$list = $borrow->where($map)->group('b.id')->order("b.id DESC")->limit($Lsql)->select();
		if($list){
			foreach ($list as &$key) {
				$member = M('members')->where("id={$key['uid']}")->find();
				$key['mid'] = $member['id'];
				$key['user_name'] = $member['user_name'];
			}

		}
		$list = $this->_listFilter($list);
		
        $this->assign("bj", array("gt"=>'大于',"eq"=>'等于',"lt"=>'小于'));
        $this->assign("list", $list);
        $this->assign("pagebar", $page);
        $this->assign("search", $search);
		$this->assign("xaction",ACTION_NAME);
        $this->assign("query", http_build_query($search));
		
        $this->display();
    }
	public function qingsuan(){
		//标号
		$id=intval($_GET['id']);
		//借款信息
		$borrower=M('Bill b')->join("lzh_members m ON b.uid=m.id")->field("b.*,m.user_name")->where("b.id =".$id)->find();
		//借款人账户
		$plat=M('Members m')->join("lzh_member_money mm ON m.id=mm.uid")->field("m.id,m.user_name,mm.account_money")->where("m.id ={$borrower['uid']}")->find();
		
		
		//投资信息
		$investors = M('Bill_invest bi')->join("lzh_members m ON bi.invest_uid=m.id")->field("m.id,m.user_name,bi.invest_amount,bi.invest_interest")->where("bi.bill_id =".$id)->select();
		
		$this->assign("plat",$plat);
		$this->assign("borrower",$borrower);
		$this->assign("investors",$investors);
		$this->display();
	}
	
	
	public function doqingsuan(){
		//标号
		$id=intval($_GET['id']);
		
		//借款信息
		$bill=M('Bill')->find($id);
		//投资信息
		$invests= M('Bill_invest')->where("bill_id={$id}")->select();
		
		//投资人返款
		$sumInterest=0;
		for($i=0;$i<count($invests);$i++){
			$iuid=$invests[$i]["invest_uid"];
			$amoney=$invests[$i]["invest_amount"]+$invests[$i]["invest_interest"];
			$backMoney= memberMoneyLog($iuid,52,$amoney,"第{$id}号标还款成功");
            if(!$backMoney) return false;
			$sumInterest+=$invests[$i]["invest_interest"];
		}
        //借款人还款	   	
		$_yue= memberMoneyLog($bill['uid'],71,-$bill['face_amount'],"第{$id}号标还款");	
		if(!$_yue) return false;
		//剩余入百财
		$_bc= memberMoneyLog(106,71,$bill['face_amount']-($bill['amount']+$sumInterest),"第{$id}号票据还款余额");	
		if(!$_bc) return false;
		
		$m=M('Bill');
	    $data['id']=$id;
	    $data['status']=8;
	    $m->save($data);
     
		$this->success("清算成功","repaymenting");	
	}
	public function done()
    {
		$map=array();
		$map['b.status'] = array("in","8");
		if(!empty($_REQUEST['uname'])&&!$_REQUEST['uid'] || $_REQUEST['uname']!=$_REQUEST['olduname']){
			$uid = M("members")->getFieldByUserName(text($_REQUEST['uname']),'id');
			$map['b.uid'] = $uid;
			$search['uid'] = $map['b.uid'];
			$search['uname'] = $_REQUEST['uname'];
		}
		if( !empty($_REQUEST['uid'])&&!isset($search['uname']) ){
			$map['b.uid'] = intval($_REQUEST['uid']);
			$search['uid'] = $map['b.uid'];
			$search['uname'] = $_REQUEST['uname'];
		}

		if(!empty($_REQUEST['bj']) && !empty($_REQUEST['money'])){
			$map['b.amount'] = array($_REQUEST['bj'],$_REQUEST['money']);
			$search['bj'] = $_REQUEST['bj'];	
			$search['money'] = $_REQUEST['money'];	
		}

		if(!empty($_REQUEST['start_time']) && !empty($_REQUEST['end_time'])){
			$timespan = strtotime(urldecode($_REQUEST['start_time'])).",".strtotime(urldecode($_REQUEST['end_time']));
			$map['b.add_time'] = array("between",$timespan);
			$search['start_time'] = urldecode($_REQUEST['start_time']);	
			$search['end_time'] = urldecode($_REQUEST['end_time']);	
		}elseif(!empty($_REQUEST['start_time'])){
			$xtime = strtotime(urldecode($_REQUEST['start_time']));
			$map['b.add_time'] = array("gt",$xtime);
			$search['start_time'] = $xtime;	
		}elseif(!empty($_REQUEST['end_time'])){
			$xtime = strtotime(urldecode($_REQUEST['end_time']));
			$map['b.add_time'] = array("lt",$xtime);
			$search['end_time'] = $xtime;	
		}
		
		//if(session('admin_is_kf')==1){
		//		$map['m.customer_id'] = session('admin_id');
		//}else{
			if($_REQUEST['customer_id'] && $_REQUEST['customer_name']){
				$map['m.customer_id'] = $_REQUEST['customer_id'];
				$search['customer_id'] = $map['m.customer_id'];	
				$search['customer_name'] = urldecode($_REQUEST['customer_name']);	
			}
			
			if($_REQUEST['customer_name'] && !$search['customer_id']){
				$cusname = urldecode($_REQUEST['customer_name']);
				$kfid = M('ausers')->getFieldByUserName($cusname,'id');
				$map['m.customer_id'] = $kfid;
				$search['customer_name'] = $cusname;	
				$search['customer_id'] = $kfid;	
			}
		//}
		//分页处理
		import("ORG.Util.Page");
		$count = M('bill b')->join("{$this->pre}members m ON m.id=b.uid")->where($map)->count('b.id');
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理

		$field= 'b.id,b.name,b.uid,b.duration,b.amount,b.interest_rate,b.deadline,m.id mid,m.user_name';
		$list = M('bill b')->field($field)->join("{$this->pre}members m ON m.id=b.uid")->where($map)->limit($Lsql)->order("b.id DESC")->select();
		$list = $this->_listFilter($list);
		
        $this->assign("bj", array("gt"=>'大于',"eq"=>'等于',"lt"=>'小于'));
        $this->assign("list", $list);
        $this->assign("pagebar", $page);
        $this->assign("search", $search);
		$this->assign("xaction",ACTION_NAME);
        $this->assign("query", http_build_query($search));
		
        $this->display();
    }
}
?>