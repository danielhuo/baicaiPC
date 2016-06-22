<?php
// 本类由系统自动生成，仅供测试用途
class BorrowAction extends HCommonAction {
    public function index(){
		$per = C('DB_PREFIX');
		if($this->uid){
			$this->assign("mstatus", M('members_status')->field(true)->find($this->uid));
			$this->assign("mdata", getMemberInfoDone($this->uid));
			$minfo = getMinfo($this->uid,true);
			$this->assign("minfo", $minfo);
			$this->assign("netMoney", getNet($this->uid));//可用净值额度
			$_allnetMoney = getFloatValue(0.9*$minfo['money_collect'],2);
			$this->assign("allnetMoney",$_allnetMoney);//总净值额度
			$this->assign("capitalinfo", getMemberBorrowScan($this->uid));
		}
        $this->assign("pagebar", $donate_list['page']);
		$this->display();
    }
	
	public function post(){
		if(!$this->uid) $this->error("请先登录",__APP__."/member/common/login/frm/borrow");
		//if(!$this->uid) $this->error("请先登录",__APP__."/member/common/login/");
		
		$vo1 = M('members')->field('id,user_name,user_email,user_pass,is_ban')->where("id={$this->uid}")->find();
		if($vo1['is_ban']==1||$vo1['is_ban']==2)  
		
		$this->error("您的帐户已被冻结，请联系客服处理！",__APP__."/borrow/index.html");
		$vminfo = M('members')->field("user_leve,time_limit,is_borrow,is_vip")->find($this->uid);
		//判断条件是否满足
		if($vminfo['is_vip']==0){
			$_xoc = M('borrow_info')->where("borrow_uid={$this->uid} AND borrow_status in(0,2,4)")->count('id');
			if($_xoc>0)  $this->error("您有一个借款中的标，请等待审核",__APP__."/member/borrowin#fragment-2");

			//$vx = M('vip_apply')->where("uid={$this->uid} AND status=0")->count("id");
			//if($vx>0) $this->error("您的VIP申请已在处理中，请耐心等待！"); 

			//if(!($vminfo['user_leve']>0 && $vminfo['time_limit']>time())) $this->error("请先通过VIP审核再发标",__APP__."/member/vip");
			
			if($vminfo['is_borrow']==0){
				$this->error("您目前不允许发布借款，如需帮助，请与客服人员联系！");
				$this->assign("waitSecond",3);
			}
	
			$mo =  M('members_status')->getFieldByUid($this->uid,'phone_status');
			if($mo['phone_status']!=1){
			$this->error("请先进行手机认证",__APP__."/member/verify?id=1#fragment-2");
		}
			
			
			
			$ids = M('members_status')->getFieldByUid($this->uid,'id_status');
			if($ids!=1){
				$this->error("请先进行实名认证并确认通过后，再发标",__APP__."/member/verify?id=1#fragment-3");
			}
			//$vo = getMemberDetail($this->uid);
			/*if($vo['province']==0 && $vo['province_now ']==0 && $vo['province_now ']==0 && $vo['city']==0 && $vo['city_now']==0 ){
				$this->error("请先填写个人详细资料后再发标",__APP__."/member/memberinfo#fragment-1");
			}*/
		}

		$gtype = text($_GET['type']);
		$vkey = md5(time().$gtype);
		switch($gtype){
			case "normal"://普通标
				$borrow_type=1;
			break;
			case "vouch"://新担保标
				$borrow_type=2;
			break;
			case "second"://秒还标
				$this->assign("miao",'yes');
				$borrow_type=3;
			break;
			case "net"://净值标
				$borrow_type=4;
			break;
			case "mortgage"://抵押标
				$borrow_type=5;
			break;
		}
		$cordon=$this->glo['borrow_cordon'];
		$unwind=$this->glo['borrow_unwind'];
		$this->assign('cordon', $cordon);
		$this->assign('unwind', $unwind);


		cookie($vkey,$borrow_type,3600);
		$borrow_duration_day = explode("|",$this->glo['borrow_duration_day']);
		$day = range($borrow_duration_day[0],$borrow_duration_day[1]);
		$day_time=array();
		foreach($day as $v){
			$day_time[$v] = $v;
		}

		$borrow_duration = explode("|",$this->glo['borrow_duration']);
		$month = range($borrow_duration[0],$borrow_duration[1]);
		$month_time=array();
		foreach($month as $v){
			$month_time[$v] = $v;
		}
		$rate_lixt = explode("|",$this->glo['rate_lixi']);
		$borrow_config = require C("APP_ROOT")."Conf/borrow_config.php";
		$this->assign("borrow_use",$this->gloconf['BORROW_USE']);
		$this->assign("borrow_min",$this->gloconf['BORROW_MIN']);
		$this->assign("borrow_max",$this->gloconf['BORROW_MAX']);
		$this->assign("borrow_time",$this->gloconf['BORROW_TIME']);
		$this->assign("BORROW_TYPE",$borrow_config['BORROW_TYPE']);
		$this->assign("borrow_type",$borrow_type);
		$this->assign("borrow_day_time",$day_time);
		$this->assign("borrow_month_time",$month_time);
		$this->assign("repayment_type",$borrow_config['REPAYMENT_TYPE']);
		$this->assign("vkey",$vkey);
		$this->assign("rate_lixt",$rate_lixt);
		
		$global=M('Global')->where("code='money_deposit'")->find();
		$this->assign("money_deposit",$global['text']);
		
		$rate_lixi = explode("|",$this->glo['rate_lixi']);
		$this->assign("rate_lixi",$rate_lixi);
		$fee_borrow_manage = explode("|",$this->glo['fee_borrow_manage']);
		$this->assign("fee_borrow_0",$fee_borrow_manage[0]/100);
		$this->assign("fee_borrow_1",$fee_borrow_manage[1]/100);
		$this->assign("fee_borrow_2",$fee_borrow_manage[2]/100);
		$this->assign("fee_borrow_monthDep",$fee_borrow_manage[3]);
		$this->assign("borrow_interest_rate_month",$rate_lixi[1]);
		$this->assign("borrow_interest_rate_day",$rate_lixi[0]);
			
		$this->display();
	}
	
	public function save(){
		if(!$this->uid) $this->error("请先登录",__APP__."/member/common/login");
		//判断是否有借款中的标
		$_xoc = M('borrow_info')->where("borrow_uid={$this->uid} AND borrow_status in(0,2,4)")->count('id');
		if($_xoc>0)  $this->error("您有一个借款中的标，请等待审核",__APP__."/member/borrowin#fragment-2");

		$pre = C('DB_PREFIX');
		//相关的判断参数
		$rate_lixt = explode("|",$this->glo['rate_lixi']);
		$borrow_duration = explode("|",$this->glo['borrow_duration']);
		$borrow_duration_day = explode("|",$this->glo['borrow_duration_day']);
		$fee_borrow_manage = explode("|",$this->glo['fee_borrow_manage']);
		$money_deposit = explode("|",$this->glo['money_deposit']);
		$vminfo = M('members m')->join("{$pre}member_info mf ON m.id=mf.uid")->field("m.user_leve,m.time_limit,mf.province_now,mf.city_now,mf.area_now")->where("m.id={$this->uid}")->find();
		//相关的判断参数
		$borrow['borrow_type'] = intval(cookie(text($_POST['vkey'])));
		//if($borrow['borrow_type']==0) $this->error("111111校验数据有误，请重新发布");
		if(strtolower($_POST['is_day'])=='yes'){
			if(floatval($_POST['borrow_interest_rate'])!=$rate_lixt[0])
				$this->error("提交的借款利率超出允许范围，请重试",0);
	    }else{
			if(floatval($_POST['borrow_interest_rate'])!=$rate_lixt[1])
				$this->error("提交的借款利率超出允许范围，请重试",0);
		}
		//if(floatval($_POST['borrow_interest_rate'])>$rate_lixt[1] || floatval($_POST['borrow_interest_rate'])<$rate_lixt[0]) $this->error("提交的借款利率超出允许范围，请重试",0);
		$borrow['borrow_money'] = intval($_POST['borrow_money']);


		$_minfo = getMinfo($this->uid,"m.pin_pass,mm.account_money,mm.back_money,mm.credit_cuse,mm.money_collect");
		$_capitalinfo = getMemberBorrowScan($this->uid);
		///////////////////////////////////////////////////////
		$borrowNum=M('borrow_info')->field("borrow_type,count(id) as num,sum(borrow_money) as money,sum(repayment_money) as repayment_money")->where("borrow_uid = {$this->uid} AND borrow_status=6 ")->group("borrow_type")->select();
		$borrowDe = array();
		foreach ($borrowNum as $k => $v) {
			$borrowDe[$v['borrow_type']] = $v['money'] - $v['repayment_money'];
		}
		
		$borrow['borrow_uid'] = $this->uid;
		$borrow['borrow_name'] = text($_POST['borrow_name']);
		$borrow['borrow_duration'] = ($borrow['borrow_type']==3)?1:intval($_POST['borrow_duration']);//秒标固定为一月
		$borrow['borrow_interest_rate'] = floatval($_POST['borrow_interest_rate']);
		if(strtolower($_POST['is_day'])=='yes') $borrow['repayment_type'] = 1;
		elseif($borrow['borrow_type']==3) $borrow['repayment_type'] = 2;//秒标按月还
		else $borrow['repayment_type'] = intval($_POST['repayment_type']?$_POST['repayment_type']:'5');//缺省是一次性还清
		if($borrow['repayment_type']=='1' || $borrow['repayment_type']=='5'){
			$borrow['total'] = 1;
		}else{
			$borrow['total'] = $borrow['borrow_duration'];//分几期还款
		}
		$borrow['borrow_status'] = 0;
		$borrow['borrow_use'] = intval($_POST['borrow_use']);
		$borrow['add_time'] = time();
		$borrow['collect_day'] = intval($_POST['borrow_time']?$_POST['borrow_time']:'7');//缺省是七天有效期
		$borrow['add_ip'] = get_client_ip();
		$borrow['borrow_info'] = text($_POST['borrow_info']);
		$borrow['reward_type'] = intval($_POST['reward_type']);
		$borrow['reward_num'] = floatval($_POST["reward_type_{$borrow['reward_type']}_value"]);
		$borrow['borrow_min'] = intval($_POST['borrow_min']?$_POST['borrow_min']:'100');//最小投标额缺省为100元
		$borrow['borrow_max'] = intval($_POST['borrow_max']);
		//$borrow['borrow_max'] = intval($_POST['borrow_max']?$_POST['borrow_max']:'99999999');//最大投标额缺省为99999999元
		/*$borrow['province'] = $vminfo['province_now'];
		$borrow['city'] = $vminfo['city_now'];
		$borrow['area'] = $vminfo['area_now'];*/
		if($_POST['is_pass']&&intval($_POST['is_pass'])==1) $borrow['password'] = md5($_POST['password']);
		$borrow['money_collect'] = floatval($_POST['moneycollect']);//代收金额限制设置
		
		
		//借款利息:费用支出
		$borrow['borrow_interest'] = getBorrowInterest($borrow['repayment_type'],$borrow['borrow_money'],$borrow['borrow_duration'],$borrow['borrow_interest_rate']);
		
		
		
		//管理费
		/*
		if($borrow['repayment_type'] == 1){//按天还
			$fee_rate = (is_numeric($fee_borrow_manage[0]))?($fee_borrow_manage[0]/100):0.001;
			$borrow['borrow_fee'] = getFloatValue($fee_rate*$borrow['borrow_money']*$borrow['borrow_duration'],2);
		}else{
			$fee_rate_1=(is_numeric($fee_borrow_manage[1]))?($fee_borrow_manage[1]/100):0.02;
			$fee_rate_2=(is_numeric($fee_borrow_manage[2]))?($fee_borrow_manage[2]/100):0.002;
			if($borrow['borrow_duration']>$fee_borrow_manage[3]&&is_numeric($fee_borrow_manage[3])){
				$borrow['borrow_fee'] = getFloatValue($fee_rate_1*$borrow['borrow_money'],2);
				$borrow['borrow_fee'] += getFloatValue($fee_rate_2*$borrow['borrow_money']*($borrow['borrow_duration']-$fee_borrow_manage[3]),2);
			}else{
				$borrow['borrow_fee'] = getFloatValue($fee_rate_1*$borrow['borrow_money'],2);
			}
		}
		*/
		$fee_borrow_manage = explode("|",$this->glo['fee_borrow_manage']);
		
		
		$borrow_fee_rate=0;
		if(strtolower($_POST['is_day'])=='yes')
			$borrow_fee_rate=$fee_borrow_manage[0]/100;
		else{
			if($borrow['borrow_duration']==0)$borrow_fee_rate=0;
			else if($borrow['borrow_duration']>=1&&$borrow['borrow_duration']<=$fee_borrow_manage[3])
				$borrow_fee_rate=$fee_borrow_manage[1]/100;
			else $borrow_fee_rate=$fee_borrow_manage[2]/100;
		}
		$money_deposit=M('Global')->where("code='money_deposit'")->find();
		$borrow['borrow_deposit']=round($borrow['borrow_money']/$money_deposit['text']);
		
		$borrow['borrow_fee']=$borrow['borrow_money']*$borrow_fee_rate*$borrow['borrow_duration'] ;
		
		
		
		if($borrow['borrow_type']==3){//秒还标
			if($borrow['reward_type']>0){
				$_reward_money = getFloatValue($borrow['borrow_money']*$borrow['reward_num']/100,2);
			}
			$_reward_money =floatval($_reward_money);
			if(($_minfo['account_money']+$_minfo['back_money'])<($borrow['borrow_fee']+$_reward_money)) $this->error("发布此标您最少需保证您的帐户余额大于等于".($borrow['borrow_fee']+$_reward_money)."元，以确保可以支付借款管理费和投标奖励费用");
		}
		
		///////////////////////////////////////////////////
		//保证金额度
		//$deposit_rate = (is_numeric($money_deposit))?($money_deposit/100):0.2; 
		switch($borrow['borrow_type']){
			case 1://普通标
				//检查信用额度
				//if($_minfo['credit_cuse']<$borrow['borrow_money']) $this->error("您的可用信用额度为{$_minfo['credit_cuse']}元，小于您准备借款的金额，不能发标");
				//检查可用余额。保证金额度暂定为20%
				$accout_num=intval($_minfo['account_money']);
				//$borrow_cost=intval($borrow['borrow_money']*$deposit_rate);
				$borrow_cost=intval($borrow['borrow_deposit']+$borrow['borrow_interest']+$borrow['borrow_fee']);
				if($accout_num<$borrow_cost) $this->error("您的可用余额为{$accout_num}元，小于您借款额".$borrow['borrow_money']."所需的保证金要求".$borrow_cost."，请先充值",__APP__."/member/charge#fragment-1 ");
			break;
			case 2://新担保标
			case 3://秒还标
			break;
			case 4://净值标
				$_netMoney = getFloatValue(0.9*$_minfo['money_collect']-$borrowDe[4],2);
				if($_netMoney<$borrow['borrow_money']) $this->error("您的净值额度{$_netMoney}元，小于您准备借款的金额，不能发标");
			break;
			case 5://抵押标
				//$borrow_type=5;
			break;
		}
		
		//投标上传图片资料（暂隐）
		foreach($_POST['swfimglist'] as $key=>$v){
			if($key>10) break;
			$row[$key]['img'] = substr($v,1);
			$row[$key]['info'] = $_POST['picinfo'][$key];
		}
		$borrow['updata']=serialize($row);
		$newid = M("borrow_info")->add($borrow);
		//echo M("borrow_info")->getlastsql();
		//die;
		$suo=array();
		$suo['id']=$newid; 
        $suo['suo']=0;
        $suoid = M("borrow_info_lock")->add($suo);
		
		if($newid) {
			/*
			$deposit_percentage = $deposit_rate * 100;
			$_freezefee = memberMoneyLog($this->uid,19,-$borrow['borrow_money']*$deposit_rate,"第{$newid}号标申请成功，冻结{$deposit_percentage}%的保证金");//冻结保证金
			
			if(!$_freezefee) $this->error("发布失败，请检查账户，然后重试");//借款者帐户处理出错*
			*/
			$this->success("借款发布成功，网站会尽快初审",__APP__."/member/borrowin#fragment-2");
		}
		else $this->error("发布失败，请先检查借款信息是否正确，然后重试");
		
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
			$this->savePathNew = C('HOME_UPLOAD_DIR').'Product/';
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

}