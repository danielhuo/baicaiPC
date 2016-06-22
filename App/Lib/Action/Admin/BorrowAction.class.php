<?php
// 全局设置
class BorrowAction extends ACommonAction
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
		$map['b.borrow_status'] = 0;
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
		
		$field= 'b.id,b.borrow_name,b.borrow_uid,b.borrow_duration,b.borrow_type,b.updata,b.borrow_money,b.borrow_fee,b.borrow_interest_rate,b.repayment_type,b.add_time,m.user_name,m.id mid,b.is_tuijian,b.money_collect';
		$list = M('borrow_info b')->field($field)->join("{$this->pre}members m ON m.id=b.borrow_uid")->where($map)->limit($Lsql)->order("b.id DESC")->select();
		
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
		$map['b.borrow_status'] = 4;
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

		$field= 'b.id,b.borrow_name,b.borrow_uid,b.borrow_duration,b.borrow_type,b.borrow_money,b.updata,b.borrow_fee,b.borrow_interest_rate,b.repayment_type,b.full_time,m.user_name,m.id mid,b.is_tuijian,b.money_collect';
		$list = M('borrow_info b')->field($field)->join("{$this->pre}members m ON m.id=b.borrow_uid")->where($map)->limit($Lsql)->order("b.id DESC")->select();
		$list = $this->_listFilter($list);
		
        $this->assign("bj", array("gt"=>'大于',"eq"=>'等于',"lt"=>'小于'));
        $this->assign("list", $list);
        $this->assign("pagebar", $page);
        $this->assign("search", $search);
		$this->assign("xaction",ACTION_NAME);
        $this->assign("query", http_build_query($search));
		
        $this->display();
    }
	
    public function waitmoney()
    {
		$map=array();
		$map['b.borrow_status'] = 2;
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

		$field= 'b.id,b.borrow_name,b.borrow_uid,b.borrow_duration,b.borrow_type,b.borrow_money,b.updata,b.borrow_fee,b.borrow_interest_rate,b.repayment_type,b.add_time,m.user_name,m.id mid,b.is_tuijian,b.has_borrow,b.money_collect';
		$list = M('borrow_info b')->field($field)->join("{$this->pre}members m ON m.id=b.borrow_uid")->where($map)->limit($Lsql)->order("b.id DESC")->select();
		$list = $this->_listFilter($list);
		foreach($list as $key=>$v){
		    $list[$key]['progress'] = getFloatValue($v['has_borrow']/$v['borrow_money']*100,2);
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
		$map['b.borrow_status'] = array('in',array(6));//还款中
		$map['b.has_transfer'] = 0;//
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

		$field= 'm.id as mid,m.customer_name,b.id,b.borrow_name,b.borrow_uid,b.borrow_duration,b.borrow_type,b.borrow_money,b.borrow_interest,b.borrow_status,b.repayment_money,b.repayment_interest,b.borrow_fee,b.borrow_interest_rate,b.repayment_type,b.deadline,m.user_name,m.user_phone,b.is_tuijian,b.money_collect';
		$list = M('borrow_info b')->field($field)->join("{$this->pre}members m ON m.id=b.borrow_uid")->where($map)->limit($Lsql)->order("b.id DESC")->select();
		$list = $this->_listFilter($list);
		
		foreach ($list as $k => $v) {
			$vx = M('investor_detail')->field('deadline,sort_order,status')->where(" borrow_id={$v['id']} AND status in(4,7) ")->order("deadline ASC")->find();
			$list[$k]['repayment_time'] = $vx['deadline'];
			$list[$k]['sort_order'] = $vx['sort_order'];
				$list[$k]['auto'] = "auto";
			//if ($vx['deadline'] < strtotime("+3 day",strtotime("today") ) )		$list[$k]['auto'] = "auto";
			//if ($vx['deadline'] < strtotime("+3 day",strtotime("today") ) && $vx['status']==7) 	$list[$k]['dai'] = "dai";
			//if ($vx['deadline'] < time() && $vx['status']==7) 	$list[$k]['dian'] = "dian";

			$need = M('investor_detail')->field(' sum(capital + interest) as need')->where(" borrow_id={$v['id']} AND deadline=$vx[deadline] ")->find();
			$list[$k]['need_money'] = $need['need'];

		}
		
        $this->assign("bj", array("gt"=>'大于',"eq"=>'等于',"lt"=>'小于'));
        $this->assign("list", $list);
        $this->assign("pagebar", $page);
        $this->assign("search", $search);
		$this->assign("xaction",ACTION_NAME);
        $this->assign("query", http_build_query($search));
		
        $this->display();
    }
	
	public function depositadding()
    {
		$map=array();
		$map['b.borrow_status'] = array('in',array(6,11));//还款中
		$map['b.has_transfer'] = 1;//
		$map['b.deposit_addition']=array('gt',0);
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

		$field= 'm.id as mid,m.customer_name,b.id,b.borrow_name,b.borrow_uid,b.borrow_duration,b.borrow_type,b.borrow_money,b.borrow_interest,b.borrow_status,b.repayment_money,b.repayment_interest,b.borrow_fee,b.borrow_interest_rate,b.repayment_type,b.deadline,m.user_name,m.user_phone,b.is_tuijian,b.money_collect';
		$list = M('borrow_info b')->field($field)->join("{$this->pre}members m ON m.id=b.borrow_uid")->where($map)->limit($Lsql)->order("b.id DESC")->select();
		$list = $this->_listFilter($list);
		
		foreach ($list as $k => $v) {
			$vx = M('investor_detail')->field('deadline,sort_order,status')->where(" borrow_id={$v['id']} AND status in(4,7) ")->order("deadline ASC")->find();
			$list[$k]['repayment_time'] = $vx['deadline'];
			$list[$k]['sort_order'] = $vx['sort_order'];
				$list[$k]['auto'] = "auto";
			//if ($vx['deadline'] < strtotime("+3 day",strtotime("today") ) )		$list[$k]['auto'] = "auto";
			//if ($vx['deadline'] < strtotime("+3 day",strtotime("today") ) && $vx['status']==7) 	$list[$k]['dai'] = "dai";
			//if ($vx['deadline'] < time() && $vx['status']==7) 	$list[$k]['dian'] = "dian";

			$need = M('investor_detail')->field(' sum(capital + interest) as need')->where(" borrow_id={$v['id']} AND deadline=$vx[deadline] ")->find();
			$list[$k]['need_money'] = $need['need'];

		}
		
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
		$map=array();
		$map['b.borrow_status'] = array('in',array(6,11));//还款中
		$map['b.has_transfer'] = 1;//
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

		$field= 'm.id as mid,m.customer_name,b.id,b.borrow_name,b.borrow_uid,b.borrow_duration,b.borrow_type,b.borrow_money,b.borrow_interest,b.borrow_status,b.repayment_money,b.repayment_interest,b.borrow_fee,b.borrow_interest_rate,b.repayment_type,b.deadline,m.user_name,m.user_phone,b.is_tuijian,b.money_collect';
		$list = M('borrow_info b')->field($field)->join("{$this->pre}members m ON m.id=b.borrow_uid")->where($map)->limit($Lsql)->order("b.id DESC")->select();
		$list = $this->_listFilter($list);
		
		foreach ($list as $k => $v) {
			$vx = M('investor_detail')->field('deadline,sort_order,status')->where(" borrow_id={$v['id']} AND status in(4,7) ")->order("deadline ASC")->find();
			$list[$k]['repayment_time'] = $vx['deadline'];
			$list[$k]['sort_order'] = $vx['sort_order'];
				$list[$k]['auto'] = "auto";
			//if ($vx['deadline'] < strtotime("+3 day",strtotime("today") ) )		$list[$k]['auto'] = "auto";
			//if ($vx['deadline'] < strtotime("+3 day",strtotime("today") ) && $vx['status']==7) 	$list[$k]['dai'] = "dai";
			//if ($vx['deadline'] < time() && $vx['status']==7) 	$list[$k]['dian'] = "dian";

			$need = M('investor_detail')->field(' sum(capital + interest) as need')->where(" borrow_id={$v['id']} AND deadline=$vx[deadline] ")->find();
			$list[$k]['need_money'] = $need['need'];

		}
		
        $this->assign("bj", array("gt"=>'大于',"eq"=>'等于',"lt"=>'小于'));
        $this->assign("list", $list);
        $this->assign("pagebar", $page);
        $this->assign("search", $search);
		$this->assign("xaction",ACTION_NAME);
        $this->assign("query", http_build_query($search));
		
        $this->display();
    }
	
    public function borrowbreak()
    {//暂时未处理
		$map['deadline'] = array("exp","<>0 AND deadline<".time()." AND `repayment_money`<`borrow_money`");
		$field= 'id,borrow_name,borrow_uid,borrow_duration,borrow_type,borrow_money,borrow_fee,repayment_money,b.updata,borrow_interest_rate,repayment_type,deadline';
		$this->_list(D('Borrow'),$field,$map,'id','DESC');
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
	
	
    public function done()
    {
		$map=array();
		$map['b.borrow_status'] = array("in","7,9");
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

		$field= 'b.id,b.borrow_name,b.borrow_uid,b.borrow_duration,b.borrow_type,b.borrow_money,b.updata,b.borrow_fee,b.borrow_interest_rate,b.repayment_type,b.repayment_money,b.deadline,m.id mid,m.user_name';
		$list = M('borrow_info b')->field($field)->join("{$this->pre}members m ON m.id=b.borrow_uid")->where($map)->limit($Lsql)->order("b.id DESC")->select();
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
        $m = D(ucfirst($this->getActionName()));
        if (false === $m->create()) {
            $this->error($m->getError());
        }
		$vm = M('borrow_info')->find($m->id);
		
	
		$rate_lixt = explode("|",$this->glo['rate_lixi']);
		$borrow_duration = explode("|",$this->glo['borrow_duration']);
		$borrow_duration_day = explode("|",$this->glo['borrow_duration_day']);
		if(floatval($_POST['borrow_interest_rate'])>$rate_lixt[1] || floatval($_POST['borrow_interest_rate'])<$rate_lixt[0]){
			$this->error("提交的借款利率超出允许范围，请重试",0);exit;
		}
		if($m->repayment_type=='1'&&($m->borrow_duration>$borrow_duration_day[1] || $m->borrow_duration<$borrow_duration_day[0])){
			$this->error("提交的借款期限超出允许范围，请去网站设置处重新设置系统参数",0);exit;
		}
		if($m->repayment_type!='1'&&($m->borrow_duration>$borrow_duration[1] || $m->borrow_duration<$borrow_duration[0])){
			$this->error("提交的借款期限超出允许范围，请去网站设置处重新设置系统参数",0);exit;
		}
		
		////////////////////图片编辑///////////////////////
		if(!empty($_POST['swfimglist'])){
			foreach($_POST['swfimglist'] as $key=>$v){
				$row[$key]['img'] = substr($v,1);
				$row[$key]['info'] = $_POST['picinfo'][$key];
			}
			$m->updata=serialize($row);
		}
		////////////////////图片编辑///////////////////////
		$vss = M("members")->field("user_phone,user_name")->where("id = {$vm['borrow_uid']}")->find();
		if($vm['borrow_status']<>2 && $m->borrow_status==2){
		  //新标提醒
			//newTip($m->id);
			MTip('chk8',$vm['borrow_uid'],$m->id);
		  //自动投标
			if($m->borrow_type==1){
				memberLimitLog($vm['borrow_uid'],1,-($m->borrow_money),$info="{$m->id}号标初审通过");
			}elseif($m->borrow_type==2){
				memberLimitLog($vm['borrow_uid'],2,-($m->borrow_money),$info="{$m->id}号标初审通过");
			}
			
			//SMStip("firstV",$vss['user_phone'],array("#USERANEM#","ID"),array($vss['user_name'],$m->id));
                          sendsms($vss['user_phone'],$m->id,19315);

		}
		elseif($m->borrow_status==1){
			MTip('chk7',$vm['borrow_uid'],$m->id);
                          sendsms($vss['user_phone'],$m->id,19465);
		}
		//if($m->borrow_status==2) $m->collect_time = strtotime("+ {$m->collect_day} days");
		if($m->borrow_status==2){ 
			$m->collect_time = strtotime("+ {$m->collect_day} days");
			//$m->is_tuijian = 1;
		}
		$m->borrow_interest = getBorrowInterest($m->repayment_type,$m->borrow_money,$m->borrow_duration,$m->borrow_interest_rate);
        //保存当前数据对象
		if($m->borrow_status==2 || $m->borrow_status==1) $m->first_verify_time = time();
		else unset($m->first_verify_time);
		unset($m->borrow_uid);
		$bs = intval($_POST['borrow_status']);

		$_P_fee = get_global_setting();
        if ($result = $m->save()) { //保存成功
			//未通过的情况下，解冻保证金
			
			/*
			if($bs==1){
				$_freezefee = memberMoneyLog($vm['borrow_uid'],19,$vm['borrow_money']*$_P_fee['money_deposit']/100,"第{$m->id}号标借款初审未通过，解冻{$_P_fee['money_deposit']}%的保证金");//冻结保证金
				if(!$_freezefee) return false;
			}*/
			if($bs==2 || $bs==1){
				$verify_info['borrow_id'] = intval($_POST['id']);
				$verify_info['deal_info'] = text($_POST['deal_info']);
				$verify_info['deal_user'] = $this->admin_id;
				$verify_info['deal_time'] = time();
				$verify_info['deal_status'] = $bs;
				if($vm['first_verify_time']>0) M('borrow_verify')->save($verify_info);
				else  M('borrow_verify')->add($verify_info);
			}
			if($vm['borrow_status']<>2 && $_POST['borrow_status']==2 && $_POST['can_auto']==1 && empty($vm['password'])==true) {
				autoInvest(intval($_POST['id']));
				$_freezefee = memberMoneyLog($vm['borrow_uid'],19,-$vm['borrow_deposit']-$vm['borrow_fee']-$vm['borrow_interest'],"第{$_POST['id']}号标借款初审通过，冻结{$vm['borrow_deposit']}的保证金");//冻结保证金
				if(!$_freezefee) return false;
			}
			//if($vm['borrow_status']<>2 && $_POST['borrow_status']==2)) autoInvest(intval($_POST['id']));
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
        $m = D(ucfirst($this->getActionName()));
        if (false === $m->create()) {
            $this->error($m->getError());
        }
		$vm = M('borrow_info')->field('borrow_uid,borrow_money,borrow_status,first_verify_time,updata,danbao,vouch_money,borrow_fee,borrow_interest_rate,borrow_duration,repayment_type,collect_day,collect_time,money_collect')->find($m->id);
		if($vm['borrow_money']<>$m->borrow_money ||
			 $vm['borrow_interest_rate']<>$m->borrow_interest_rate ||
			 $vm['borrow_duration']<>$m->borrow_duration ||
			 $vm['repayment_type']<>$m->repayment_type ||
			 $vm['borrow_fee'] <> $m->borrow_fee
		  ){
			$this->error('复审中的借款不能再更改‘还款方式’，‘借款金额’，‘年化利率’，‘借款期限’,‘借款管理费’');
			exit;
		}


		if($m->borrow_status<>5 && $m->borrow_status<>6){
			$this->error('已经满标的的借款只能改为复审通过或者复审未通过');
			exit;
		}

		////////////////////图片编辑///////////////////////
		if(!empty($_POST['swfimglist'])){
			foreach($_POST['swfimglist'] as $key=>$v){
				$row[$key]['img'] = substr($v,1);
				$row[$key]['info'] = $_POST['picinfo'][$key];
			}
			$m->updata=serialize($row);
		}
		////////////////////图片编辑///////////////////////
		//复审投标检测
		//$capital_sum1=M('investor_detail')->where("borrow_id={$m->id}")->sum('capital');
		$capital_sum2=M('borrow_investor')->where("borrow_id={$m->id}")->sum('investor_capital');
		if(($vm['borrow_money']!=$capital_sum2)){
			$this->error('投标金额不统一，请确认！');
			exit;
		}
                
		$vss = M("members")->field("user_phone,user_name")->where("id = {$vm['borrow_uid']}")->find();        
		if($m->borrow_status==6){//复审通过
			$appid = borrowApproved($m->id);
			if(!$appid) $this->error("复审失败");
		  
			MTip('chk9',$vm['borrow_uid'],$m->id);//SMStip("approve",$vss['user_phone'],array("#USERANEM#","ID"),array($vss['user_name'],$m->id));
			sendsms($vss['user_phone'],$m->id,19309);
		}elseif($m->borrow_status==5){//复审未通过
			$appid = borrowRefuse($m->id,3);
			if(!$appid) $this->error("复审失败");
			MTip('chk12',$vm['borrow_uid'],$m->id);
			sendsms($vss['user_phone'],$m->id,19487);
		}       
                  //保存当前数据对象
		$m->second_verify_time = time();
		unset($m->borrow_uid);
		$bs = intval($_POST['borrow_status']);
                  if ($result = $m->save()) { //保存成功
		        M()->execute("update lzh_borrow_investor set birth_time =".time()." where borrow_id = ".$_POST['id']);
				$verify_info['borrow_id'] = intval($_POST['id']);
				$verify_info['deal_info_2'] = text($_POST['deal_info_2']);
				$verify_info['deal_user_2'] = $this->admin_id;
				$verify_info['deal_time_2'] = time();
				$verify_info['deal_status_2'] = $bs;
				if($vm['first_verify_time']>0) M('borrow_verify')->save($verify_info);
				else  M('borrow_verify')->add($verify_info);
			alogs("borrowApproved",$result,1,'复审操作成功！');//管理员操作日志
            //成功提示
            $this->assign('jumpUrl', __URL__."/".session('listaction'));
            $this->success(L('修改成功'));
        } else {
			alogs("borrowApproved",$result,0,'复审操作失败！');//管理员操作日志
            //失败提示
            $this->error(L('修改失败'));
		}	
	}


	

	
	
		
			
    public function doEditrepaymenting(){//修改还款中的标
        $m = D(ucfirst($this->getActionName()));
        if (false === $m->create()) {
            $this->error($m->getError());
        }
		$vm = M('borrow_info')->field('borrow_uid,borrow_status,borrow_type,first_verify_time,password,updata,danbao,vouch_money,money_collect,can_auto')->find($m->id);
		$vm['can_auto'] = $_POST['can_auto'];
		$rate_lixt = explode("|",$this->glo['rate_lixi']);
		$borrow_duration = explode("|",$this->glo['borrow_duration']);
		$borrow_duration_day = explode("|",$this->glo['borrow_duration_day']);
		if(floatval($_POST['borrow_interest_rate'])>$rate_lixt[1] || floatval($_POST['borrow_interest_rate'])<$rate_lixt[0]){
			$this->error("提交的借款利率超出允许范围，请重试",0);exit;
		}
		if($m->repayment_type=='1'&&($m->borrow_duration>$borrow_duration_day[1] || $m->borrow_duration<$borrow_duration_day[0])){
			$this->error("提交的借款期限超出允许范围，请去网站设置处重新设置系统参数",0);exit;
		}
		if($m->repayment_type!='1'&&($m->borrow_duration>$borrow_duration[1] || $m->borrow_duration<$borrow_duration[0])){
			$this->error("提交的借款期限超出允许范围，请去网站设置处重新设置系统参数",0);exit;
		}
		
		////////////////////图片编辑///////////////////////
		if(!empty($_POST['swfimglist'])){
			foreach($_POST['swfimglist'] as $key=>$v){
				$row[$key]['img'] = substr($v,1);
				$row[$key]['info'] = $_POST['picinfo'][$key];
			}
			$m->updata=serialize($row);
		}
		////////////////////图片编辑///////////////////////
		
		if($vm['borrow_status']<>2 && $m->borrow_status==2){
		  //新标提醒
			//newTip($m->id);
			MTip('chk8',$vm['borrow_uid'],$m->id);
		  //自动投标
			if($m->borrow_type==1){
				memberLimitLog($vm['borrow_uid'],1,-($m->borrow_money),$info="{$m->id}号标初审通过");
			}elseif($m->borrow_type==2){
				memberLimitLog($vm['borrow_uid'],2,-($m->borrow_money),$info="{$m->id}号标初审通过");
			}
			$vss = M("members")->field("user_phone,user_name")->where("id = {$vm['borrow_uid']}")->find();
			SMStip("firstV",$vss['user_phone'],array("#USERANEM#","ID"),array($vss['user_name'],$m->id));
		}
		//if($m->borrow_status==2) $m->collect_time = strtotime("+ {$m->collect_day} days");
		if($m->borrow_status==2){ 
			$m->collect_time = strtotime("+ {$m->collect_day} days");
			//$m->is_tuijian = 1;
		}
		$m->borrow_interest = getBorrowInterest($m->repayment_type,$m->borrow_money,$m->borrow_duration,$m->borrow_interest_rate);
        //保存当前数据对象
		if($m->borrow_status==2 || $m->borrow_status==1) $m->first_verify_time = time();
		else unset($m->first_verify_time);
		unset($m->borrow_uid);
		$bs = intval($_POST['borrow_status']);
        if ($result = $m->save()) { //保存成功
			if($bs==2 || $bs==1){
				$verify_info['borrow_id'] = intval($_POST['id']);
				$verify_info['deal_info'] = text($_POST['deal_info']);
				$verify_info['deal_user'] = $this->admin_id;
				$verify_info['deal_time'] = time();
				$verify_info['deal_status'] = $bs;
				if($vm['first_verify_time']>0) M('borrow_verify')->save($verify_info);
				else  M('borrow_verify')->add($verify_info);
			}
			if($vm['borrow_status']<>2 && $_POST['borrow_status']==2 && $_POST['can_auto']==1 && empty($vm['password'])==true) {
				autoInvest(intval($_POST['id']));
			}
			//if($vm['borrow_status']<>2 && $_POST['borrow_status']==2)) autoInvest(intval($_POST['id']));
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
		$Bconfig = require C("APP_ROOT")."Conf/borrow_config.php";
	 	$listType = $Bconfig['REPAYMENT_TYPE'];
	 	$BType = $Bconfig['BORROW_TYPE'];
		$row=array();
		$aUser = get_admin_name();
		foreach($list as $key=>$v){
			$v['repayment_type_num'] = $v['repayment_type'];
			$v['repayment_type'] = $listType[$v['repayment_type']];
			$v['borrow_type'] = $BType[$v['borrow_type']];
			if($v['deadline']) $v['overdue'] = getLeftTime($v['deadline']) * (-1);
			if($v['borrow_status']==1 || $v['borrow_status']==3 || $v['borrow_status']==5){
				$v['deal_uname_2'] = $aUser[$v['deal_user_2']];
				$v['deal_uname'] = $aUser[$v['deal_user']];
			}

			$v['last_money'] = $v['borrow_money']-$v['has_borrow'];//新增剩余金额
			if($v['is_auto']==1){
				$v['is_auto']="自动投标";
			}else{
				$v['is_auto']="手动投标";
			}
			
			$row[$key]=$v;
		}
		return $row;
	}
	
	
	 public function doweek()
    {
		$map=array();
		$map['b.borrow_status'] = 6;
		if(!empty($_REQUEST['isShow'])){
			$week_1 = array(strtotime(date("Y-m-d",time())." 00:00:00"),strtotime("+6 day",strtotime(date("Y-m-d",time())." 23:59:59")));//一周内
			$map['d.deadline'] = array("between",$week_1);
		}
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
		
		//分页处理
		import("ORG.Util.Page");
		$borrow = M('borrow_info b');
		$join = "{$this->pre}investor_detail d on b.id=d.borrow_id";
		$count = $borrow ->join($join)->where($map)->count('DISTINCT b.id');
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理
		$field= 'b.id,b.borrow_name,b.borrow_uid,b.borrow_duration,b.borrow_type,b.borrow_money,b.borrow_fee,b.borrow_interest_rate,b.repayment_type,d.deadline';
		$list = $borrow->field($field)->join($join)->where($map)->group('b.id')->order("b.id DESC")->limit($Lsql)->select();
		if($list){
			foreach ($list as &$key) {
				$member = M('members')->where("id={$key['borrow_uid']}")->find();
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
	
	//swf上传图片
	public function swfUpload(){
		if($_POST['picpath']){
			$imgpath = substr($_POST['picpath'],1);
			if(in_array($imgpath,$_SESSION['imgfiles'])){
					 unlink(C("WEB_ROOT").$imgpath);
					 $thumb = get_thumb_pic($imgpath);
				$res = unlink(C("WEB_ROOT").$thumb);
				if($res) $this->success("删除成功","",$_POST['oid']);
				else $this->error("删除失败","",$_POST['oid']);
			}else{
				$this->error("图片不存在","",$_POST['oid']);
			}
		}else{
			$this->savePathNew = C('ADMIN_UPLOAD_DIR').'Product/' ;
			$this->thumbMaxWidth = C('PRODUCT_UPLOAD_W');
			$this->thumbMaxHeight = C('PRODUCT_UPLOAD_H');
			$this->saveRule = date("YmdHis",time()).rand(0,1000);
			$info = $this->CUpload();
			$data['product_thumb'] = $info[0]['savepath'].$info[0]['savename'];
			if(!isset($_SESSION['count_file'])) $_SESSION['count_file']=1;
			else $_SESSION['count_file']++;
			$_SESSION['imgfiles'][$_SESSION['count_file']] = $data['product_thumb'];
			echo "{$_SESSION['count_file']}:".__ROOT__."/".$data['product_thumb'];//返回给前台显示缩略图
		}
	}
	
	//人工处理满标但未进入复审列表的数据
	public function dowaitMoneyComplete(){
		$pre = C('DB_PREFIX');
		$borrow_id = $_REQUEST['id'];
		$upborrowsql = "update `{$pre}borrow_info` set ";
		$upborrowsql .= "`borrow_status`= 4,`full_time`=".time();
		$upborrowsql .= " WHERE `id`={$borrow_id}";
		
		$result = M()->execute($upborrowsql);
		if($result) {
			alogs("dowaitMoneyComplete",0,1,'人工处理满标但未进入复审列表的数据操作成功！');//管理员操作日志
			$this->success("处理成功");
			$this->assign('jumpUrl', __URL__."/".session('listaction'));
		}else{
			alogs("dowaitMoneyComplete",0,0,'人工处理满标但未进入复审列表的数据操作失败！');//管理员操作日志
			$this->error("处理失败");
			$this->assign('jumpUrl', __URL__."/".session('listaction'));
		}
	}
	
	//邮件提醒
	  public function tip() {
	  	$id = intval($_REQUEST['id']);
		$vm = M('borrow_info')->field('borrow_uid,borrow_name,borrow_money,repayment_type,deadline')->find($id);
		$borrowName = $vm['borrow_name'];
		$borrowMoney = $vm['borrow_money'];
		if($id){
			Notice(9,$vm['borrow_uid'],array('id'=>$id,'borrowName'=>$borrowName,'borrowMoney'=>$borrowMoney));
			ajaxmsg();
		}
		else ajaxmsg('',0);
	}
	
	//每个借款标的投资人记录
	 public function doinvest()
    {
		$borrow_id = intval($_REQUEST['borrow_id']);
		$map=array();
		
		$map['bi.borrow_id'] = $borrow_id;
		//分页处理
		import("ORG.Util.Page");
		$count = M('borrow_investor bi')->join("{$this->pre}members m ON m.id=bi.investor_uid")->where($map)->count('bi.id');
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理

		$field= 'bi.id bid,b.id,bi.investor_capital,bi.investor_interest,bi.invest_fee,bi.add_time,bi.is_auto,m.user_name,m.id mid,m.user_phone,b.borrow_duration,b.repayment_type,m.customer_name,b.borrow_type,b.borrow_name';
		$list = M('borrow_investor bi')->field($field)->join("{$this->pre}members m ON m.id=bi.investor_uid")->join("{$this->pre}borrow_info b ON b.id=bi.borrow_id")->where($map)->limit($Lsql)->order("bi.id DESC")->select();
		$list = $this->_listFilter($list);
		
		//dump($list);exit;
        $this->assign("list", $list);
        $this->assign("pagebar", $page);
        $this->display();
    }
	
	/////////////////////////////////////新增未满标的人工满标应急处理  2014-06-13 fan 开始//////////////////////////////////
	 public function borrowfull(){
		$map=array();
		$map['b.borrow_status'] = 2;
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
		
		$map['b.borrow_min'] = array("exp","> (b.borrow_money-b.has_borrow)");
		//分页处理
		import("ORG.Util.Page");
		$count = M('borrow_info b')->join("{$this->pre}members m ON m.id=b.borrow_uid")->where($map)->count('b.id');
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理

		$field= 'b.id,b.borrow_name,b.borrow_uid,b.borrow_duration,b.borrow_type,b.borrow_money,m.user_name,m.id mid,b.is_tuijian,b.has_borrow,b.money_collect,b.borrow_min';
		$list = M('borrow_info b')->field($field)->join("{$this->pre}members m ON m.id=b.borrow_uid")->where($map)->limit($Lsql)->order("b.id DESC")->select();
		
		$list = $this->_listFilter($list);
		$vo = M('members')->field("id,user_name")->select();//查询出所有会员
		$userlist = array();
		if(is_array($vo)){
			foreach($vo as $key => $v){
				foreach($list as $key1 => $v1){
					if($v['id']!=$v1['borrow_uid']){
						$userlist[$v['id']]=$v['user_name'];
					}
				}
			}
		}
		$this->assign("userlist",$userlist);//流转会员
        $this->assign("bj", array("gt"=>'大于',"eq"=>'等于',"lt"=>'小于'));
        $this->assign("list", $list);
        $this->assign("pagebar", $page);
        $this->assign("search", $search);
		$this->assign("xaction",ACTION_NAME);
        $this->assign("query", http_build_query($search));
		
        $this->display();
    }
	
	//人工处理低于最小投标金额无法正常满标的情况
	
	public function doMoneyComplete(){
		$pre = C('DB_PREFIX');
		$borrow_id = $_REQUEST['id'];
		$money = intval($_POST['lastmoney']);
		$uid = $_REQUEST['uid'];
		if(empty($uid)){
			$this->error("请选择投资人");
		}
		$vm = M('borrow_info')->field('borrow_status')->find($borrow_id);
		if(($vm['borrow_status']!=2)){
			$this->error('该标借款状态不在借款中，无法执行满标处理！');
			exit;
		}else{
			$done = investMoney($uid,$borrow_id,$money);
			if($done===true) {
				alogs("doMoneyComplete",0,1,'人工处理低于最小投标金额无法正常满标的数据操作成功！');//管理员操作日志
				$this->success("恭喜成功投标{$money}元");
			}else if($done){
				$this->error($done);
			}else{
				alogs("doMoneyComplete",0,1,'人工处理低于最小投标金额无法正常满标的数据操作成功！');//管理员操作日志
				$this->error("对不起，投标失败，请重试!");
			}
		}
	}
	public function huakuan(){
		//标号
		$borrow_id=$_GET['borrow_id'];
		
		//标信息
		$borrow_info=M('borrow_info')->where("id=".$borrow_id)->find();
		
		//借款人号
		$borrow_uid=$borrow_info['borrow_uid'];
		//借款人信息
		$borrower=M('Members')->where("id=".$borrow_uid)->find();
		//投资人信息
		$investors = M('Borrow_investor bi')->join("lzh_members m ON bi.investor_uid=m.id")->field("m.id,m.user_name,bi.investor_capital,bi.investor_interest")->where("bi.borrow_id=".$borrow_id)->select();
		
		
		$this->assign("borrow_id",$borrow_id);
		$this->assign("borrow_info",$borrow_info);
		
		$this->assign('borrow_deposit',$borrow_info['borrow_deposit']);
		$this->assign("borrower",$borrower);
		$this->assign("investors",$investors);
		$this->display();
	}
	public function dohuakuan(){
		//标号
		$borrow_id=$_POST['borrow_id'];
		
		//目标账户
		$account1=$_POST['account1'];
		$account2=$_POST['account2'];
		//标信息
		$borrow_info=M('borrow_info')->where("id=".$borrow_id)->find();
		//借款人账户信息
		$member_money=M('member_money')->where("uid=".$borrow_info.borrow_uid)->find();
		//借款人冻结资金里的管理费流入百財网账户
		$borrow_fee=(float)$borrow_info['borrow_fee'];
		$_unfreezefee= memberMoneyLog($borrow_info['borrow_uid'],72,-$borrow_fee,"第{$borrow_id}号标划款成功，管理费从冻结金额里扣除");	
		if(!$_unfreezefee) return false;
		$_unfreezefee= memberMoneyLog($account1,18,$borrow_fee,"第{$borrow_id}号标划款成功，百財网获得管理费");	
		if(!$_unfreezefee) return false;
		//借款人配资款流入黄马甲
		$peizi_money=(float)$borrow_info['borrow_money']+(float)$borrow_info['borrow_deposit'];
		$_peizi= memberMoneyLog($account2,71,$peizi_money,"第{$borrow_id}号标划款成功，配资款入黄马甲");	
		if(!$_peizi) return false;
		
		//借款人冻结资金里的利息流入投资人账户
		$borrow_interest=(float)$borrow_info['borrow_interest'];
		$_unfreezeInterest= memberMoneyLog($borrow_info['borrow_uid'],72,-$borrow_interest,"第{$borrow_id}号标划款成功，利息从冻结金额里扣除");	
		if(!$_unfreezeInterest) return false;
		
		$investors = M('Borrow_investor bi')->join("lzh_member_money mm ON bi.investor_uid=mm.uid")->field("mm.uid,mm.account_money,bi.investor_interest,mm.money_collect")->where("bi.borrow_id=".$borrow_id)->select();
		for($i=0;$i<count($investors);$i++){
			$_getInterest= memberMoneyLog($investors[$i]["uid"],761,$investors[$i]["investor_interest"],"第{$borrow_id}号标划款成功，待收利息入余额");
            if(!$_getInterest) return false;			
		}
		
		$borrow_info=M('Borrow_info');	
	    $data['id']=$borrow_id;
	    $data['has_transfer']=1;
	    $borrow_info->save($data);            
		
		$this->success("划款成功","transferring");
	}
	public function qingsuan(){
		//黄马甲1
		$hmj_id=107;
		$hmj=M('Member_money mm')->join("lzh_members m ON mm.uid=m.id")->where("mm.uid=".$hmj_id)->find();
		//标号
		$borrow_id=$_GET['borrow_id'];
		
		//标信息
		$borrow_info=M('borrow_info')->where("id=".$borrow_id)->find();
		//借款人号
		$borrow_uid=$borrow_info['borrow_uid'];
		//借款人信息
		$borrower=M('Members')->where("id=".$borrow_uid)->find();
		
		//投资人投资信息
		$investors = M('Borrow_investor bi')
		             ->join("lzh_members m ON bi.investor_uid=m.id")
					 ->field("m.id,m.user_name,bi.investor_capital")
		             ->where("bi.borrow_id=".$borrow_id." and bi.debt_uid=0")->select();
		$debtors = M('Borrow_investor bi')
		             ->join("lzh_members m ON bi.debt_uid=m.id")
					 ->field("m.id,m.user_name,bi.investor_capital")
		             ->where("bi.borrow_id=".$borrow_id." and bi.debt_uid<>0")->select();
        					 
		$this->assign("hmj",$hmj);
		$this->assign("borrow_info",$borrow_info);
		$this->assign("borrower",$borrower);
		$this->assign("investors",$investors);
		$this->assign("debtors",$debtors);
		
		$this->display();
	}
	
	//追保审核页面
	public function depositverify(){
		$borrow_id=$_GET['id'];
		$vo=M('borrow_info')
		   ->field("id,borrow_name,borrow_money,borrow_deposit,borrow_duration,deposit_addition")
		   ->where("id={$borrow_id}")->find();
		$this->assign("vo",$vo);
		$this->display();
		
	}
	public function dodepositadd(){
		$m = D(ucfirst($this->getActionName()));
        if (false === $m->create()) {
            $this->error($m->getError());
        }
		$borrow_id=$_POST['id'];
		
		$borrow_info=M('Borrow_info')->where("id={$borrow_id}")->find();
		$deposit_addition=(float)($borrow_info['deposit_addition']);
		$bi=M('Borrow_info');
		$data['id']=$borrow_id;
		if($_POST['status']==1){
			MTip('chk101',$borrow_info['borrow_uid'],$borrow_id);
			$_peizi= memberMoneyLog($borrow_info['borrow_uid'],189,$deposit_addition,"第{$borrow_id}号标追加保证金成功,冻结的保证金入配资");
		    if(!$_peizi)$this->error('审核失败','depositadding');
		    memberMoneyLog(107,0,$deposit_addition,"第{$borrow_id}号标追加保证金成功,保证金入黄马甲");
			$data['borrow_deposit']=(float)$borrow_info['borrow_deposit']+$deposit_addition;
		}else{
			
			MTip('chk201',$borrow_info['borrow_uid'],$borrow_id);
			memberMoneyLog($borrow_info['borrow_uid'],101,$deposit_addition,"第{$borrow_id}号标追加保证金审核失败,追加额解冻");
			
		}
		
		$data['deposit_addition']=0;
		
		if ($result = $bi->save($data)){
			alogs("doEditdepositadd",$result,1,'追保操作成功！');
			
		}else{
			alogs("doEditdepositadd",$result,0,'追保操作失败！');
		}
		$this->success('操作成功','depositadding');
		
	}
	public function doqingsuan(){
		//黄马甲1
		$hmj_id=$_POST['hmj_id'];
		$hmj=M('Member_money mm')->join("lzh_members m ON mm.uid=m.id")->where("mm.uid=".$hmj_id)->find();
		//余额清算
		$liquidate=$_POST['liquidate'];
		//标号
		$borrow_id=$_POST['borrow_id'];	
		//标信息
		$borrow_info=M('borrow_info')->where("id=".$borrow_id)->find();
		//借款人号
		$borrow_uid=$borrow_info['borrow_uid'];
		//借款人信息
		$borrower=M('Members')->where("id=".$borrow_uid)->find();
		
		//黄马甲扣除余额		   	
		$_yue= memberMoneyLog($hmj_id,71,-$liquidate,"第{$borrow_id}号标清算成功，余额划出");	
		if(!$_yue) return false;
		
		//借款人账户
		$_jieyu= memberMoneyLog($borrow_uid,71,(float)$liquidate-(float)$borrow_info['borrow_money'],"第{$borrow_id}号标清算成功，结余入账");	
		if(!$_jieyu) return false;	
		
		$_peizi=memberMoneyLog($borrow_uid,190,-$borrow_info['borrow_money'],"第{$borrow_id}号标清算成功,借款出配资");//
		if(!$_peizi) return false;
		$_deposit=memberMoneyLog($borrow_uid,191,-$borrow_info['borrow_deposit'],"第{$borrow_id}号标清算成功,保证金出配资");//
		if(!$_deposit) return false;
        		
        //投资人信息
		$investors = M('Borrow_investor bi')
		             ->join("lzh_members m ON bi.investor_uid=m.id")
					 ->field("m.id,m.user_name,bi.investor_capital,bi.status")
		             ->where("bi.borrow_id=".$borrow_id." and bi.debt_uid=0")->select();
		$debtors = M('Borrow_investor bi')
		             ->join("lzh_members m ON bi.debt_uid=m.id")
					 ->field("m.id,m.user_name,bi.investor_capital,bi.status")
		             ->where("bi.borrow_id=".$borrow_id." and bi.debt_uid<>0")->select();
		//投资人收回投资
		for($i=0;$i<count($investors);$i++){
			$_investMoney= memberMoneyLog($investors[$i]["id"],52,$investors[$i]["investor_capital"],"第{$borrow_id}号标清算成功，投资返帐");
            if(!$_investMoney) return false;
		}
        for($i=0;$i<count($debtors);$i++){
			
			$_debtMoney= memberMoneyLog($debtors[$i]["id"],52,$debtors[$i]["investor_capital"],"第{$borrow_id}号标清算成功，投资返帐");
            if(!$_debtMoney) return false;
		}		
		$m=M('Borrow_info');
	    $data['id']=$borrow_id;
	    $data['borrow_status']=7;
	    $m->save($data);
        
		M()->execute("update lzh_borrow_investor set status = 5 where borrow_id =".$borrow_id);
        
		
		$this->success("清算成功","repaymenting");
		
	}
 	/////////////////////////////////////新增未满标的人工满标应急处理  2014-06-13 fan 结束//////////////////////////////////
}
?>