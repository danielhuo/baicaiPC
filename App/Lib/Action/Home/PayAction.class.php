<?php
// 本类由系统自动生成，仅供测试用途
class PayAction extends HCommonAction {
	var $paydetail = NULL;
	var $payConfig = NULL;
	var $locked = false;
	var $return_url = "";
	var $notice_url = "";
	var $member_url = "";
	
	public function _Myinit(){
		$this->return_url = "http://".$_SERVER['HTTP_HOST']."/Pay/payreturn";
		$this->notice_url = "http://".$_SERVER['HTTP_HOST']."/Pay/paynotice";
		$this->member_url = "http://".$_SERVER['HTTP_HOST']."/member";
		$this->payConfig = FS("Webconfig/payconfig");
		
		$this->baofoback_url = "http://".$_SERVER['HTTP_HOST']."/pay/paybaofoback";//返回宝付前台
		$this->baofonotice_url = "http://".$_SERVER['HTTP_HOST']."/pay/paybaofonotice";//返回宝付后台
		
		
		
	}
	
	public function offline(){
		$this->getPaydetail();
		$this->paydetail['money'] = floatval($_POST['money_off']);
		//本地要保存的信息

        $payimg_arr = $_POST['swfimglist'];
        if(count($payimg_arr)){
            $this->paydetail['payimg'] = serialize($payimg_arr);
        }else{
            $this->paydetail['payimg'] = '';
        }

        $config = FS("Webconfig/payoff"); 
        $bank_id = intval($_POST['bank'])-1;
		$this->paydetail['fee'] = 0;
		$this->paydetail['nid'] = 'offline';
		$this->paydetail['way'] = 'off';
		$this->paydetail['tran_id'] = text($_POST['tran_id']);
		$this->paydetail['off_bank'] = $config['BANK'][$bank_id]['bank'].' 开户名：'.$config['BANK'][$bank_id]['payee'];
		$this->paydetail['off_way'] = text($_POST['off_way']);
		$newid = M('member_payonline')->add($this->paydetail);
		if($newid){
			$this->success("线下充值提交成功，请等待管理员审核",__APP__."/member");
		}else{
			$this->error("线下充值提交失败，请重试");
		}
	}
	
	
	
	//升级后宝付接口
	public function baofoo(){
		if($this->payConfig['baofoo']['enable'] == 0)
		{
			exit( "对不起，该支付方式被关闭，暂时不能使用!" );
		}
		$this->getPaydetail( );
        $submitdata['MemberID'] = $this->payConfig['baofoo']['MemberID'];//商户号
        $submitdata['TerminalID'] = $this->payConfig['baofoo']['TerminalID'];//'18161';//终端号
        $submitdata['InterfaceVersion'] = '4.0';//接口版本号
		$submitdata['KeyType'] = 1;//接口版本号
		$submitdata['PayID'] = "";
		$submitdata['TradeDate'] = date("Ymdhis");//交易时间
		$submitdata['TransID'] = date("YmdHis").mt_rand( 1000, 9999 );//流水号
		$submitdata['OrderMoney'] = number_format( $this->paydetail['money'], 2, ".", "" ) * 100;
		$submitdata['ProductName'] = urlencode($this->glo['web_name']."帐户充值" );
		$submitdata['Amount'] = "1";
		$submitdata['Username'] = "";
		$submitdata['AdditionalInfo'] = "";
		$submitdata['PageUrl'] = $this->baofoback_url;
		$submitdata['ReturnUrl'] = $this->baofonotice_url;
		$submitdata['NoticeType'] = "1";
		$submitdata['Signature'] = $this->getSign("baofoo", $submitdata);
		unset( $this->paydetail['bank']);
		$this->paydetail['fee'] = getfloatvalue( $this->payConfig['baofoo']['feerate'] * $this->paydetail['money']/100, 2 );
		$this->paydetail['nid'] = $this->createnid("baofoo", $submitdata['TransID']);
		$this->paydetail['way'] = "baofoo";
		M("member_payonline")->add( $this->paydetail );

		if ($_SERVER['HTTPS'] != "on") {
			//$url = "http://vgw.baofoo.com/payindex"; //测试
			$url = "http://gw.baofoo.com/payindex"; //正式

		 }else{

			//$url = "http://vgw.baofoo.com/payindex"; //测试
			$url = "https://gw.baofoo.com/payindex"; //正式

		}
		$this->create( $submitdata, $url );//正式
		//$this->create( $submitdata, "http://gw.baofoo.com/payindex" );//正式
	}
	
	public function payreturn(){
		$payid = $_REQUEST['payid'];
		switch($payid){
			
		case "baofoo" :
			$recode = $_REQUEST['Result'];
			if($recode == "1"){
				$signGet = $this->getSign( "baofoo_return", $_REQUEST );
				$nid = $this->createnid( "baofoo", $_REQUEST['TransID'] );
				if ( $_REQUEST['Md5Sign'] == $signGet )
				{
					$this->success( "充值完成", __APP__."/member/" );
				}
				else
				{
					$this->error( "签名不付", __APP__."/member/" );
				}
			}else{
				$this->error(auto_charset($_REQUEST['resultDesc']), __APP__."/member/" );
			}
		break;
		
		}
	}
	
	public function paynotice(){
		$payid = $_REQUEST['payid'];
		switch($payid){
			
			case "baofoo" :
				$recode = $_REQUEST['Result'];
				if ( $recode == "1" )
				{
					$signGet = $this->getSign( "baofoo_return", $_REQUEST );
					$nid = $this->createnid( "baofoo", $_REQUEST['TransID'] );
					if ($_REQUEST['Md5Sign'] == $signGet){
						$done = $this->payDone(1,$nid,$_REQUEST['TransID']);
					}else{
						$done = $this->payDone(2,$nid,$_REQUEST['TransID']);
					}
				}else{
					$done = $this->payDone(3, $nid );
				}
				if($done===true) echo "OK";
				else echo "Fail";
			break;
		}
	}
		
	//////////////////////////////////////////新宝付接口处理方法开始    shao2014-01-26/////////////////////////////
	public function paybaofoback(){
		$recode = $_REQUEST['Result'];
			if($recode == "1"){
				$signGet = $this->getSign( "baofoo_return", $_REQUEST );
				
				if ( $_REQUEST['Md5Sign'] == $signGet )
				{
					$this->success( "充值完成", __APP__."/member/" );
				}
				else
				{
					$this->error( "签名不付", __APP__."/member/" );
				}
			}else{
				$this->error(auto_charset($_REQUEST['resultDesc']), __APP__."/member/" );
			}

			$signGet = $this->getSign("baofoo_return", $_REQUEST );
			//print_r($recode);
			//die;
				if ($recode == "1"){
					$nid = $this->createnid("baofoo", $_REQUEST['TransID'] );
					if ($_REQUEST['Md5Sign'] == $signGet){
						$done = $this->payDone(1,$nid,$_REQUEST['TransID']);
					}else{
						$done = $this->payDone(2,$nid,$_REQUEST['TransID']);
					}
				}else{
					$done = $this->payDone(3, $nid);
				}
				if($done===true){
					echo "OK";
				}else{
				 	echo "Fail";
				}

			}
	public function paybaofonotice(){
		$request_str="";
		foreach ($_REQUEST as $k => $v) {
			$request_str.=$k.'='.$v.'&';
		}

		$recode = $_REQUEST['Result'];
			$signGet = $this->getSign("baofoo_return", $_REQUEST );
			
			
				if ($recode == "1"){
					$nid = $this->createnid("baofoo", $_REQUEST['TransID'] );
					if ($_REQUEST['Md5Sign'] == $signGet){
						$done = $this->payDone(1,$nid,$_REQUEST['TransID']);
					}else{
						$done = $this->payDone(2,$nid,$_REQUEST['TransID']);
					}
				}else{
					$done = $this->payDone(3, $nid);
				}
				if($done===true){
					echo "OK";

				}else{
				 	echo "Fail";
				}
				$data['text']=$request_str.'signGet='.$signGet.'&nid='.$nid;
				$data['add_time']=time();
				M('Jubao')->add($data);
	}
	//////////////////////////////////////////新宝付接口处理方法结束    shao2014-01-26///////////////////////////
	
	private function payDone($status,$nid,$oid){
		$done = false;
		$Moneylog = D('member_payonline');
		if($this->locked) return false;
		$this->locked = true;
		switch($status){
			case 1:
				$updata['status'] = $status;
				$updata['tran_id'] = text($oid);
				$vo = M('member_payonline')->field('uid,money,fee,status')->where("nid='{$nid}'")->find();
				if($vo['status']!=0 || !is_array($vo)) return;
				$xid = $Moneylog->where("uid={$vo['uid']} AND nid='{$nid}'")->save($updata);
				
				$tmoney = floatval($vo['money'] - $vo['fee']);
				if($xid) $newid = memberMoneyLog($vo['uid'],3,$tmoney,"充值订单号:".$oid,0,'@网站管理员@');//更新成功才充值,避免重复充值 
				//if(!$newid){
				//	$updata['status'] = 0;
				//	$Moneylog->where("uid={$vo['uid']} AND nid='{$nid}'")->save($updata);
				//	return false;
				//}
				$vx = M("members")->field("user_phone,user_name")->find($vo['uid']);
				SMStip("payonline",$vx['user_phone'],array("#USERANEM#","#MONEY#"),array($vx['user_name'],$vo['money']));
			break;
			case 2:
				$updata['status'] = $status;
				$updata['tran_id'] = text($oid);
				$xid = $Moneylog->where("uid={$vo['uid']} AND nid='{$nid}'")->save($updata);
			break;
			case 3:
				$updata['status'] = $status;
				$xid = $Moneylog->where("uid={$vo['uid']} AND nid='{$nid}'")->save($updata);
			break;
		}
		
		if($status>0){
			if($xid) $done = true;
		}
		$this->locked = false;
		return $done;
	}
	
	private function createnid($type,$static){
			return md5("XXXXX@@#$%".$type.$static);
	}
	
	private function getPaydetail(){
		if(!$this->uid) exit;
		$this->paydetail['money'] = getFloatValue($_GET['t_money'],2);
		$this->paydetail['fee'] = 0;
		$this->paydetail['add_time'] = time();
		$this->paydetail['add_ip'] = get_client_ip();
		$this->paydetail['status'] = 0;
		$this->paydetail['uid'] = $this->uid;
		$this->paydetail['bank'] = strtoupper($_GET['bankCode']);
	}
	
	private function getSign($type,$data){
		$md5str="";
		switch($type){
			case "baofoo":
				$signarray = array( "MemberID", "PayID", "TradeDate", "TransID", "OrderMoney", "PageUrl", "ReturnUrl", "NoticeType" );
				foreach ($signarray as $v){
					$md5str .= $data[$v].'|';
				}
				$md5str .= $this->payConfig['baofoo']['pkey'];
                
				$md5str = md5($md5str);
				return $md5str;
			break;
			case "baofoo_return":
				$signarray = array( "MemberID", "TerminalID", "TransID", "Result", "ResultDesc", "FactMoney", "AdditionalInfo",'SuccTime' );
				foreach ($signarray as $v){
					$md5str .= "$v".'='.$data[$v].'~|~';
				}
				//dump($md5str);
				$md5str .= 'Md5Sign='.$this->payConfig['baofoo']['pkey'];
				$md5str = md5( $md5str );
				return $md5str;
			break;
		}
	}
	
	private function create($data,$submitUrl){
		$inputstr = "";
		foreach($data as $key=>$v){
			$inputstr .= '
		<input type="hidden"  id="'.$key.'" name="'.$key.'" value="'.$v.'"/>
		';
		}
		
		$form = '
		<form action="'.$submitUrl.'" name="pay" id="pay" method="POST">
';
		$form.=	$inputstr;
		$form.=	'
</form>
		';
		
		$html = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>请不要关闭页面,支付跳转中.....</title>
        </head>
<body>
        ';
        $html.=	$form;
        $html.=	'
        <script type="text/javascript">
			document.getElementById("pay").submit();
		</script>
        ';
        $html.= '
        </body>
</html>
		';
				 
		Mheader('utf-8');
		echo $html;
		exit;
	}
}