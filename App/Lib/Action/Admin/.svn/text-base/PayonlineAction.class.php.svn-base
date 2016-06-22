<?php
// 全局设置
class PayonlineAction extends ACommonAction
{
    /**
    +----------------------------------------------------------
    * 默认操作
    +----------------------------------------------------------
    */
    public function index()
    {
		$payconfig = FS("Webconfig/payconfig");

		$this->assign('guofubao_config',$payconfig['guofubao']);	//国付宝
		$this->assign('ips_config',$payconfig['ips']);				//环迅支付
		$this->assign('chinabank_config',$payconfig['chinabank']);	//网银在线
		$this->assign('baofoo_config', $payconfig['baofoo']);		//宝付
		$this->assign('shengpay_config', $payconfig['shengpay']);	//盛付通
		$this->assign('tenpay_config', $payconfig['tenpay']);		//财付通
		$this->assign('ecpss_config', $payconfig['ecpss']);			//汇潮支付
		$this->assign('easypay_config', $payconfig['easypay']);		//易生支付
		$this->assign('cmpay_config', $payconfig['cmpay']);			//中国移动支付
		$this->assign('allinpay_config',$payconfig['allinpay']);	//通联支付
		$this->assign('sina_config',$payconfig['sina']);			//新浪微支付
		$this->assign('sumapay_config', $payconfig['sumapay']);		//丰付
		$this->assign('epay_config', $payconfig['95epay']);			//双乾支付
        $this->display();
    }
    public function save()
    {
		FS("payconfig",$_POST['pay'],"Webconfig/");
		alogs("Payonline",0,1,'执行了第三方支付接口参数的编辑操作！');//管理员操作日志
		$this->success("操作成功",__URL__."/index/");
    }
}
?>