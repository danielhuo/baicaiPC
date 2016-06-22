4<?php
// 全局设置
class LoanAction extends ACommonAction
{
	   
 	public function  publish(){
	    $Loanfig = require C("APP_ROOT")."Conf/loan_config.php";
		$Config = require C("APP_ROOT")."Conf/config.php";
		$this->assign('name', $Loanfig);
		$this->assign('method',$Config);
		$this->display();
    }

	public function dopublish(){
			
		$Loanfig = require C("APP_ROOT")."Conf/loan_config.php";
		$map['user_name'] = text($_POST['name']);
		$index =1;
		$method = 0;
		
		$data['mortgage'] = $_POST['mortgage'];
		$data['loan_amount'] = $_POST['loan_amount'];
		$data['loan_duration'] = $_POST['loan_duration'];
		$data['collect_day'] = $_POST['collect_day'];
		$data['interest_rate'] = $_POST['interest_rate'];
		$data['fee_rate'] = $_POST['fee_rate'];
		$data['min_invest'] = $_POST['min_invest'];
		$data['loan_detail'] = $_POST['detail'];
		$data['duration_type'] = 0;
		
		if (empty($map['user_name']) || empty($data['loan_duration']) || empty($data['collect_day']) ||
			empty($data['interest_rate']) || empty($data['fee_rate']) || empty($data['loan_detail'])||
			empty($data['min_invest'])){
			ajaxmsg("数据不完整",1);
		}	
		if ($data['mortgage'] < 0 || $data['min_invest'] < 0 || $data['loan_amount']<0){
			ajaxmsg("金额输入错误，请重试",1);
			
		}
		if ($data['interest_rate'] > 100|| $data['fee_rate'] > 100){
			ajaxmsg("利率输入错误，请重试",1);
			
		}
		
		
		$data['loan_type'] = $index;
		$data['repay_type'] = $method;
		$data['loan_name'] = $Loanfig['type'][$index];
		
		
		$uid = M("members")->where($map)->find();
		if (!$uid) {
			ajaxmsg("用户不存在！",1);
		
		}else{
			$data['loan_uid'] = $uid['id'];
		} 
		
		$bank = M('member_banks')->where("uid = {$uid['id']}")->find();
		if(!$bank){
			ajaxmsg("用户未绑定银行卡！",1);
			
		}
		/*上传文件开始*/
		$this->saveRule = 'uniqid';
		$this->savePathNew = 'UF/Uploads/Loan/' ;
		$this->thumbMaxWidth = "200";
		$this->thumbMaxHeight = "200";
		$info = $this->CUpload();
		if($info&&$info['status']==0)
			ajaxmsg($info['info'],1);
		$info=$info['info'];
		/*上传文件结束*/
		$data['birth_time'] = time();
		$data['collect_time'] = strtotime("+7 days");
		$newid = M('loan')->add($data);
		
		if ($newid) {
			
			$type=array();
			foreach($_FILES as $key=>$value){
				switch ($key){
					case 'minfo':
					for($i=0;$i<count($value['name']);$i++){
						$type[] = 0;
					}
					break;
					case 'linfo':
					for($i=0;$i<count($value['name']);$i++){
						$type[] = 1;
					}
					break;
					case 'binfo':
					for($i=0;$i<count($value['name']);$i++){
						$type[] = 2;
					}
					break;
				}
			}
			

			for($i=0;$i<count($info);$i++){
				$data1['url'] = $info[$i]['savepath'].$info[$i]['savename'];
				$data1['thumb_url'] = $info[$i]['savepath'].'thumb_'.$info[$i]['savename'];
				$data1['type'] = $type[$i];
				$data1['lid'] = $newid ;
				$mid = M('loan_img')->add($data1);
			}
		
			$loan_deal=array(
				'type'=>'publish',
				'lid'=>$newid,
				'uid'=>$this->admin_id,
				'info'=>'',
				'time'=>time(),
				'result'=>1
				);
				
			M('loan_deal')->add($loan_deal);
			ajaxmsg("发布成功",2);
		}else{
			ajaxmsg("发布失败",1);
		
		} 
		
	}

	/*检验用户能否借款*/
	public function verifyname(){
		$uname = text($_POST['name']);
		$uid = M('members')->getFieldByUserName($uname,'id');		
		if(!$uid){
			ajaxmsg("用户尚未注册！",1);
		}else{
			$minfo=M('member_info mi')
			->join("{$this->pre}member_banks mb on mi.uid=mb.uid")
			->field("mi.real_name,mi.idcard,mb.bank_num")
			->where("mi.uid=$uid")
			->find();
			if ($minfo) {
				$minfo['status']=2;
				ajaxmsg("已认证",0);	
			}else{
				ajaxmsg("用户未绑定银行卡！",1);
			}
		}	
	}
	
    public function waitverify()
    {
		$map=array();
		$map['b.status'] = 0;
		//分页处理
		import("ORG.Util.Page");
		$count = M('loan b')->join("{$this->pre}members m ON m.id=b.loan_uid")->where($map)->count('b.id');
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理
		
		$field= 'b.id,b.loan_name,b.loan_uid,b.loan_duration,b.duration_type,b.loan_amount,b.interest_rate,b.fee_rate,b.collect_day,m.user_name,m.id mid';
		$list = M('loan b')->field($field)->join("{$this->pre}members m ON m.id=b.loan_uid")->where($map)->limit($Lsql)->order("b.id DESC")->select();
	
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
		//分页处理
		import("ORG.Util.Page");
		$count = M('loan b')->join("{$this->pre}members m ON m.id=b.loan_uid")->where($map)->count('b.id');
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理
		
		$list = M('loan b')->field("b.*,m.user_name")->join("{$this->pre}members m ON m.id=b.loan_uid")->where($map)->limit($Lsql)->order("b.id DESC")->select();
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
		$map['b.status'] = 2;
		//分页处理
		import("ORG.Util.Page");
		$count = M('loan b')->join("{$this->pre}members m ON m.id=b.loan_uid")->where($map)->count('b.id');
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理

		$field= 'b.*,m.user_name';
		$list = M('loan b')->field($field)->join("{$this->pre}members m ON m.id=b.loan_uid")->where($map)->limit($Lsql)->order("b.id DESC")->select();
		$list = $this->_listFilter($list);
		foreach($list as $key=>$v){
		    $list[$key]['progress'] = getFloatValue($v['has_collect']/$v['loan_amount']*100,2);
			$list[$key]['isflow'] = time()>$v['collect_time']?1:0;
		}
        $this->assign("bj", array("gt"=>'大于',"eq"=>'等于',"lt"=>'小于'));
        $this->assign("list", $list);
        $this->assign("pagebar", $page);
        $this->assign("search", $search);
		$this->assign("xaction",ACTION_NAME);
        $this->assign("query", http_build_query($search));
		
        $this->display();
    }
	
	
	public function doflow(){
		$id=$_GET['id'];
        $vm = M('loan')->find($id);
		$vo = M('loan_invest')->where("loan_id = {$id}")->select();
		for($i=0;$i<count($vo);$i++){
			$backMoney = memberMoneyLog($vo[$i]['invest_uid'],8,$vo[$i]['invest_amount'],"第{$id}号标流标，冻结金额解冻");
			if(!$backMoney) return false;
		}
		$vm['status']=3;
		$result=M('loan')->save($vm);
		$loan_deal=array(
				'type'=>'unfinish',
				'lid'=>$id,
				'uid'=>$this->admin_id,
				'info'=>'',
				'time'=>time(),
				'result'=>$result
				);
		M('loan_deal')->add($loan_deal);
		if($result){
			$this->assign('jumpUrl', __URL__."/".session('listaction'));
			$this->success(L('流标成功'));
			
		}else{
			$this->error(L('流标失败'));
		}
		
	}

	public function transferring()
    {
		$map=array();
		$map['b.status'] = 6;//划款中
		
		//分页处理
		import("ORG.Util.Page");
		$count = M('loan b')->join("{$this->pre}members m ON m.id=b.loan_uid")->where($map)->count('b.id');

		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理

		$list = M('loan b')->field("b.*,m.user_name,m.user_phone,m.id as mid")->join("{$this->pre}members m ON m.id=b.loan_uid")->where($map)->limit($Lsql)->order("b.id DESC")->select();
		
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
		$map=array();
		$map['b.status'] = 7 ;//还款中
		//分页处理
		import("ORG.Util.Page");
		$count = M('loan b')->join("{$this->pre}members m ON m.id=b.loan_uid")->where($map)->count('b.id');
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理

		$field= 'm.id as mid,b.id,b.loan_name,b.has_collect,b.loan_uid,b.loan_duration,b.duration_type,b.loan_amount,b.status,b.begin_time,b.deadline,b.interest_rate,b.fee_rate,m.user_name,m.user_phone';
		$list = M('loan b')->field($field)->join("{$this->pre}members m ON m.id=b.loan_uid")->where($map)->limit($Lsql)->order("b.id DESC")->select();
		$list = $this->_listFilter($list);
		
        $this->assign("bj", array("gt"=>'大于',"eq"=>'等于',"lt"=>'小于'));
        $this->assign("list", $list);
        $this->assign("pagebar", $page);
        $this->assign("search", $search);
		$this->assign("xaction",ACTION_NAME);
        $this->assign("query", http_build_query($search));
		
        $this->display();
    }
	
    
	
	public function unfinish(){
		$map=array();
		$map['l.status'] = 3;
		//分页处理
		import("ORG.Util.Page");
		$count = M('loan l')->join("{$this->pre}members m ON m.id=l.loan_uid")->where($map)->count('l.id');
		
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理

		$field= 'l.*,m.id as mid,m.user_name,d.unfinish_uid,d.unfinish_time,d.unfinish_info';
		$list = M('loan l')->field($field)->join("{$this->pre}members m ON m.id=l.loan_uid")->join("{$this->pre}loan_deal d ON l.id=d.id")->where($map)->limit($Lsql)->order("l.id DESC")->select();
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
		$map['l.status'] = 1;
		$map['ld.type'] = 'verify';
		//分页处理
		import("ORG.Util.Page");
		$count = M('loan l')->join("{$this->pre}members m ON m.id=l.loan_uid")->where($map)->count('l.id');
		
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理
		$field="l.*,m.user_name,m.id as mid,ld.id as lid ,ld.info,ld.time,ld.uid";
		$list = M('loan l')->field($field)->join("{$this->pre}members m ON m.id=l.loan_uid")->join("{$this->pre}loan_deal ld ON l.id=ld.lid")->where($map)->limit($Lsql)->order("l.id DESC")->select();
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
		$map['l.status'] = 5;
		//分页处理
		import("ORG.Util.Page");
		$count = M('loan l')->join("{$this->pre}members m ON m.id=l.loan_uid")->where($map)->count('l.id');
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理

		$field= 'l.*,m.user_name,m.id as mid,d.id as did ,d.review_uid,d.review_time,d.review_info';
		$list = M('loan l')->field($field)->join("{$this->pre}members m ON m.id=l.loan_uid")->join("{$this->pre}loan_deal d ON l.id=d.id")->where($map)->limit($Lsql)->order("l.id DESC")->select();
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
		$Bconfig = require C("APP_ROOT")."Conf/loan_config.php";
		$borrow_status = $Bconfig['STATUS'];
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
					if(in_array($i,array("3","4")) ) continue;
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
		
		$danbao = M('article')->field("id,title")->where("type_id =7")->select();//M()->query($sql);
		$dblist = array();
		if(is_array($danbao)){
			foreach($danbao as $key => $v){
				$dblist[$v['id']]=$v['title'];
			}
		}
		$loan = M("loan")->field("loan_uid")->find($id);
		$member = M('members')->field("user_name")->find($loan['loan_uid']);
		$this->assign('user_name',$member['user_name']);
		$this->assign("danbao_list",$dblist);//新增担保标A+
		//////////////////////////////////////////////////////////////////////////////
		$this->assign('xact',session('listaction'));
		$btype = $Bconfig['REPAYMENT_TYPE'];
		$this->assign("vv",M("loan_deal")->find($id));
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
		$firstview_info=$_POST['deal_info'];
		
		$loan=M('loan')->find($id);
		if($status==2){ 
			$loan['collect_time']= strtotime("+ {$loan['collect_day']} days");
			
		}elseif($status!=1){
			$this->error(L('请选择是否通过！'));
		}
		$loan['status']=$status;
		$result=M('loan')->save($loan);
		$result=$result?1:0;
		$loan_deal=array(
				'type'=>'verify',
				'lid'=>$id,
				'uid'=>$this->admin_id,
				'info'=>$firstview_info,
				'time'=>time(),
				'result'=>$result
				);
		M('loan_deal')->add($loan_deal);
        if($result) { 
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
        $vm = M('loan')->find($id);
		$vo = M('loan_invest')->where("loan_id = {$id}")->select();
		
		if($status != 5 && $status != 6){
			$this->error(L('请选择是否通过！'));
		}
		$vm['status'] = $status;
		$result = M('loan')->save($vm);
		$result=$result?1:0;
		$loan_deal=array(
				'type'=>'verify2',
				'lid'=>$id,
				'uid'=>$this->admin_id,
				'info'=>$_POST['deal_info_2'],
				'time'=>time(),
				'result'=>$result
				);
		M('loan_deal')->add($loan_deal);
		if($result){
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
	public function doEditWaitmoney(){
        $id=intval($_POST['id']);
		$status=intval($_POST['status']);
        $vm = M('loan')->find($id);
		$vo = M('loan_invest')->where("loan_id = {$id}")->select();
		
		if($status == 3){
			for($i=0;$i<count($vo);$i++){
				$backMoney = memberMoneyLog($vo[$i]['invest_uid'],8,$vo[$i]['invest_amount'],"第{$id}号标流标，冻结金额解冻");
				if(!$backMoney) return false;
			}	
		}elseif($status != 4){
			$this->error(L('请选择操作方式！'));
		}
		
		$vm['status'] = $status;
		$result=M('loan')->save($vm);
		$result=$result?1:0;
		$loan_deal=array(
				'type'=>'waitmoney',
				'lid'=>$id,
				'uid'=>$this->admin_id,
				'info'=>'',
				'time'=>time(),
				'result'=>$result
				);
		M('loan_deal')->add($loan_deal);

		if($result){
			alogs("waitmoney",$result,1,'募止未满操作成功！');
			$this->assign('jumpUrl', __URL__."/".session('listaction'));
			$this->success(L('招标中的借款操作修改成功！'));
			
		}else{
			alogs("waitmoney",$result,0,'募止未满操失败！');
			$this->error(L('招标中的借款操作修改失败！'));
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
	public function schedule(){
		$id = intval($_REQUEST['id']);
		$cancel_warrants="";
		$fund_trust="";
		$reowner="";
		$dates = M('loan_schedule')->find($id);
		if ($dates) {
			if ($dates['cancel_warrants'])
				$cancel_warrants=date("Y-m-d",$dates['cancel_warrants']);
			
			if ($dates['fund_trust']) 
				$fund_trust=date("Y-m-d",$dates['fund_trust']);
			
			if ($dates['reowner']) 
				$reowner=date("Y-m-d",$dates['reowner']);
			
		}

		$this->assign("cancel_warrants",$cancel_warrants);
		$this->assign("fund_trust",$fund_trust);
		$this->assign("reowner",$reowner);
		$this->assign("id",$id);
		$this->display();
	}
	//每个借款标的投资人记录
	 public function doschedule(){
		$data['id'] = $_POST['id'];
		$cancel_warrants=$_REQUEST['cancel_warrants'];
		$fund_trust=$_REQUEST['fund_trust'];
		$reowner=$_REQUEST['reowner'];
		if(!empty($cancel_warrants))
			$cancel_warrants=strtotime(urldecode($cancel_warrants));
		if(!empty($fund_trust))
			$fund_trust=strtotime(urldecode($fund_trust));
		if(!empty($reowner))
			$reowner=strtotime(urldecode($reowner));
		$data['cancel_warrants']=$cancel_warrants;
		$data['fund_trust']=$fund_trust;
		$data['reowner']=$reowner;
		$count = M('loan_schedule')->where("id ={$data['id']}")->count();
		if ($count){
			$id = M('loan_schedule')->save($data);
		}else{
			$id = M('loan_schedule')->add($data);
		}
		if($id){
			 $this->success(L('更新成功'));
		}else{
			$this->error($id);
		}
		
		
    }
	
	//每个借款标的投资人记录
	 public function doinvest()
    {
		$id = intval($_REQUEST['id']);
		$map=array();
		
		$map['li.loan_id'] = $id;
		//分页处理
		import("ORG.Util.Page");
		$count = M('loan_invest li')
			->join("{$this->pre}members m ON m.id=li.invest_uid")
			->where($map)
			->count('li.id');
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理

		$list = M('loan_invest li')
			->join("{$this->pre}members m ON m.id=li.invest_uid")
			->join("{$this->pre}loan l ON l.id=li.loan_id")
			->where($map)
			->limit($Lsql)
			->order("li.id DESC")
			->select();
		$list = $this->_listFilter($list);
		
		//dump($list);exit;
		
        $this->assign("list", $list);
        $this->assign("pagebar", $page);
        $this->display();
    }
	
	
	public function huakuan(){
		//标号
		$id=intval($_GET['id']);
		//借款信息
		$borrower=M('loan l')->join("lzh_members m ON l.loan_uid=m.id")->field("l.id,m.id as mid,m.user_name,l.loan_amount,l.has_collect")->where("l.id =".$id)->find();
		
		//投资信息
		$investors = M('loan_invest li')->join("lzh_members m ON li.invest_uid=m.id")->field("m.id,m.user_name,li.invest_amount,li.invest_interest")->where("li.loan_id =".$id)->select();
		
		$this->assign("borrower",$borrower);
		$this->assign("investors",$investors);
		$this->display();
	}
	
	public function dohuakuan(){
		//标号
		$id=intval($_GET['id']);
		//借款信息
		$loan=M('loan')->find($id);
		$collect_time = strtotime(date('Ymd',$loan['collect_time']));
		
		if(time()<$collect_time) $this->error('请于起息日当天划款!');
		//投资信息
		$invests= M('loan_invest')->where("loan_id={$id}")->select();
		//融资入账
		$dealBill=memberMoneyLog($loan["loan_uid"],17,$loan["has_collect"],"第{$id}{$loan['loan_name']}融资成功");
		if(!$dealBill) return false;
		//投资出冻结,入待收
		for($i=0;$i<count($invests);$i++){
			$uid=$invests[$i]["invest_uid"];
			$amount=$invests[$i]["invest_amount"];
			$dealInvest= memberMoneyLog($uid,15,$amount,"第{$id}{$loan['loan_name']}投资成功,投资出账");
			$jug=invest_award($id,$uid,$amount);
            if(!$dealInvest||!$jug) return false;
		}
		//保存还款截止日
		$endTime = strtotime(date("Y-m-d",time())." ".$this->glo['back_time']);
		
		$str_duration=$loan['duration_type'] == 0?"day":"month";
		$loan['begin_time']=time();
		$loan['deadline']=strtotime("+{$loan['loan_duration']} ".$str_duration,$endTime);

	    $loan['status']=7;
	    $result=M('loan')->save($loan); 

	    $result=$result?1:0;
		$loan_deal=array(
				'type'=>'huakuan',
				'lid'=>$id,
				'uid'=>$this->admin_id,
				'info'=>'',
				'time'=>time(),
				'result'=>$result
				);
		M('loan_deal')->add($loan_deal);           
		if($result){
			alogs("transfer",$result,1,'划款成功！');
            $this->assign('jumpUrl', __URL__."/".session('listaction'));
            $this->success(L('划款成功'));
		}else {
			alogs("transfer",$result,0,'划款失败！');
            $this->error("划款失败");
		}	
	}
	public function doweek()
    {
		$map=array();
		$map['b.status'] =7;
		$map['b.deadline'] =array("elt",strtotime(date("Y-m-d"))+86400*7);
		
		//分页处理
		import("ORG.Util.Page");
		$borrow = M('Loan b');
	
		$count = $borrow->where($map)->count('DISTINCT b.id');
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理
		$list = $borrow->where($map)->group('b.id')->order("b.id DESC")->limit($Lsql)->select();
		if($list){
			foreach ($list as &$key) {
				$member = M('members')->where("id={$key['loan_uid']}")->find();
				$key['duration_type']==1?$key['duration_type2']='个月':$key['duration_type2']='天';
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

    public function repaylist(){

    	$map=array();
		//分页处理
		import("ORG.Util.Page");
		$count =M('loan_repay p')
			->join("{$this->pre}loan l on p.lid=l.id")
			->join("{$this->pre}members m on l.loan_uid=m.id")
			->count('p.id');
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		$order= "p.status,p.deadline";
		//分页处理
		
		$list = M('loan_repay p')
			->join("{$this->pre}loan l on p.lid=l.id")
			->join("{$this->pre}members m on l.loan_uid=m.id")
			->order($order)
			->limit($Lsql)
			->field("p.*,l.loan_name,l.has_collect,l.pay_periods,m.user_name,m.id as uid")
			->select();
		$list = $this->_listFilter($list);
	
        $this->assign("list", $list);
        $this->assign("pagebar", $page);
		$this->assign("xaction",ACTION_NAME);
        $this->display();

    }

    public function repay(){
    	$id=$_GET['id'];
		$repay=M('loan_repay p')
			->join("{$this->pre}loan l on p.lid=l.id")
			->join("{$this->pre}members m on l.loan_uid=m.id")
			->field("p.repay_amount,p.num_period,m.user_name,l.pay_periods,l.fee")
			->where("p.id=$id")
			->find();
		$returns=M('loan_invest_return t')
			->join("{$this->pre}loan_invest i on t.iid=i.id")
			->join("{$this->pre}members m on i.invest_uid=m.id")
			->field("t.return_amount,m.user_name")
			->where("t.rid=$id")
			->select();
		if($repay['num_period']==$repay['pay_periods']){
			array_push($returns,array('user_name'=>"百财平台","return_amount"=>$repay['fee']));
		}
		$this->assign("repay",$repay);
		$this->assign("id",$id);
		$this->assign("returns",$returns);
		$this->display();
    }

    public function dorepay(){
    	$repay_id=$_GET['id'];
		$repay=M('loan_repay p')
			->join("{$this->pre}loan l on p.lid=l.id")
			->field("p.repay_amount,p.num_period,l.id as lid,l.loan_name,l.loan_uid as uid,l.pay_periods,l.fee")
			->where("p.id=$repay_id")
			->find();
		$returns=M('loan_invest_return t')
			->join("{$this->pre}loan_invest i on t.iid=i.id")
			->field("t.return_amount,i.invest_uid as uid")
			->where("t.rid=$repay_id")
			->select();
		$sucStr="JK{$repay['lid']}{$repay['loan_name']}第{$repay['num_period']}/{$repay['pay_periods']}期还款成功";
		//借款人还款
		if(accountMoney($repay['uid'])<$repay['repay_amount']) 
			$this->error('还款账户余额不足');
		$_yue= memberMoneyLog($repay['uid'],51,-$repay['repay_amount'],$sucStr);	
		if(!$_yue) return false;
		//投资人返款
		for($i=0;$i<count($returns);$i++){
			$backMoney= memberMoneyLog($returns[$i]['uid'],52,$returns[$i]['return_amount'],$sucStr);
			if(!$backMoney) return false;
		}
		if($repay['num_period']==$repay['pay_periods']){
			$_bc= memberMoneyLog(106,53,$repay['fee'],"JK{$repay['lid']}{$repay['loan_name']}管理费到账");	
			if(!$_bc) return false;
			$update_loan=M('loan')->where("id={$repay['lid']}")->setField("status",8);
			if (!$update_loan) return false;
		}
		$loan_repay['id']=$repay_id;
		$loan_repay['status']=1;
		$loan_repay['repay_time']=time();
		$result= M('loan_repay')->save($loan_repay);

		$result=$result?1:0;
		$loan_deal=array(
				'type'=>'repay',
				'lid'=>$repay['lid'],
				'uid'=>$this->admin_id,
				'info'=>'',
				'time'=>time(),
				'result'=>$result
				);
		M('loan_deal')->add($loan_deal);           

		if($result){
			alogs("repay",$result,1,"JK{$repay['lid']}{$repay['loan_name']}还款操作成功");//管理员操作日志
            //成功提示
            $this->assign('jumpUrl', __URL__."/".session('listaction'));
            $this->success(L('还款成功'));
		}else {
			alogs("repay",$result,0,"JK{$repay['lid']}{$repay['loan_name']}还款操作失败");//管理员操作日志
            //失败提示
            $this->error("还款失败");
		}
		
    }
	public function qingsuan(){
		//标号
		$id=intval($_GET['id']);
		//借款信息
		$loan=M('loan l')->join("lzh_members m ON l.loan_uid=m.id")->field("l.*,m.user_name")->where("l.id =".$id)->find();
		$days=count_day(time(),$loan['begin_time']);
		$loan['repaydays']=$days;
		$loan['interest'] = count_profit($loan['has_collect'],$days,$loan['interest_rate']);
		$loan['fee'] = count_profit($loan['has_collect'],$days,$loan['fee_rate']);
		$loan['need_repay']=$loan['loan_amount']+$loan['interest']+$loan['fee'];
		//借款人账户
		$plat=M('Members m')->join("lzh_member_money mm ON m.id=mm.uid")->field("m.id,m.user_name,mm.account_money")->where("m.id ={$loan['loan_uid']}")->find();
		
		//投资信息
		$investors = M('loan_invest li')->join("lzh_members m ON li.invest_uid=m.id")->field("m.id,m.user_name,li.invest_amount,li.invest_interest")->where("li.loan_id =".$id)->select();
		foreach ($investors as $k=> $v) {

			$investors[$k]['invest_interest']=count_profit($v['invest_amount'],$days,$loan['interest_rate']);;
		}
		
		$this->assign("plat",$plat);
		$this->assign("loan",$loan);
		$this->assign("investors",$investors);
		$this->display();
	}
	
	
	public function doqingsuan(){
		//标号
		$id=intval($_GET['id']);
		//借款信息
		$loan=M('loan')->find($id);
		$days=count_day(time(),$loan['begin_time']);
		$loan['repaytime']=time();
		$loan['repaydays']=$days;
		$loan['interest'] = count_profit($loan['has_collect'],$days,$loan['interest_rate']);
		$loan['fee'] = count_profit($loan['has_collect'],$days,$loan['fee_rate']);
		//借款人信息
		$mm=getMinfo($loan['loan_uid'],"m.id,m.user_name,mm.account_money");
		if($mm['account_money']<$loan['has_collect']+$loan['interest']+$loan['fee'])
			 $this->error('还款账户余额不足');
		//投资信息
		$invests= M('loan_invest')->where("loan_id={$id}")->select();
		
		//投资人返款
		foreach ($invests as $k=> $v) {
			$v['invest_interest']=count_profit($v['invest_amount'],$days,$loan['interest_rate']);
			$backinvest= memberMoneyLog($v['invest_uid'],52,$v["invest_amount"],"第{$id}号标投资本金回款成功",$mm['id'],$mm['user_name']);
			$backinterest= memberMoneyLog($v['invest_uid'],51,$v["invest_interest"],"第{$id}号标投资付息成功",$mm['id'],$mm['user_name']);
			if(!$backinvest||!$backinterest||!M('loan_invest')->save($v)) return false;
		}
        //借款人还款	   	
		$_yue= memberMoneyLog($mm['id'],51,-$loan['has_collect']-$loan['interest']-$loan['fee'],"第{$id}号标还款");	
		if(!$_yue) return false;
		//剩余入百财
		$_bc= memberMoneyLog(106,53,$loan['fee'],"第{$id}号管理费用");	
		if(!$_bc) return false;
		
	    $loan['status']=8;
		if(M('loan')->save($loan)){
			alogs("qingsuan",1,1,'清算成功！');//管理员操作日志
            //成功提示
            $this->assign('jumpUrl', __URL__."/".session('listaction'));
            $this->success(L('清算成功'));
		}else {
			alogs("qingsuan",0,0,'清算失败！');//管理员操作日志
            //失败提示
            $this->error("清算失败");
		}
	}

	public function done()
    {
		$map=array();
		$map['b.status'] = 8;
	
		import("ORG.Util.Page");
		$count = M('loan b')->join("{$this->pre}members m ON m.id=b.loan_uid")->where($map)->count('b.id');
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
	
		$list = M('loan b')->field("b.*,m.id mid,m.user_name")->join("{$this->pre}members m ON m.id=b.loan_uid")->where($map)->limit($Lsql)->order("b.id DESC")->select();
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