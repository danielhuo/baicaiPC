<?php
// 本类由系统自动生成，仅供测试用途
class BillAction extends MCommonAction {
	public function apply(){
		$this->display();
	}
	public function save(){
		$img = $_FILES['img'];
		if(!$img) $this->error("请先上传票据图片！");
		$info = $this->uploadIdimgs();
		$img0 = $info[0]['savepath'].$info[0]['savename'];
		if ($img0){
			$data['bill_img'] = $img0;
		}else{
			$this->error("图片上传失败！");
		}
		$map['user_name'] = text($_POST['name']);
		$data['face_amount'] = $_POST['money'];
		$data['deadline'] = strtotime(urldecode($_REQUEST['time']));
		$data['interest_rate'] = $_POST['interest'];
		$data['duration']= ceil(($data['deadline']-time())/86400);
		$data['amount'] =$_POST['amount'];
		$data['interest'] = round($data['amount'] * $data['interest_rate']/100 * $data['duration']/360,2);
		$uid = M('members')->field("id")->where($map)->find();
		if (!$uid) {
			$this->error("用户不存在！");
		}else{
			$data['uid'] = $uid['id'];
		} 
		if ($data['amount'] < 1) $this->error("融资额不能小于1元！");
		if ($data['deadline'] < time()) $this->error("还款期限必须晚于今天！");
		if ($data['interest_rate'] > 10) $this->error("年化收益不能大于10%！");
		$data['name']="票据融资项目";
		$data['collect_day']=7;
		$data['birthtime'] = time();
		$data['statuts'] = 0;
		$newid = M('bill')->add($data);
		if ($newid) {
			$this->success("借款发布成功，网站会尽快初审",__APP__."/member/");
		}else{
			$this->error("发布失败，请先检查借款信息是否正确，然后重试");
		} 
	}
	public function yzname(){
		$map['user_name'] = text($_POST['name']);
		$uid = M('members')->field("id")->where($map)->find();
		$count = count($uid);
		$bank = M('member_banks')->field("bank_name")->where("uid = {$uid['id']}")->count();
		if ($count > 0){
			if ($bank) {
				ajaxmsg("",1);
			}else{
				ajaxmsg("用户未绑定银行卡！",0);
			}
		}else{
			ajaxmsg("用户名错误！",0);
		}
		
	}
	function uploadIdimgs(){
			import("ORG.Net.UploadFile");
			$upload = new UploadFile();
	
			$upload->saveRule =uniqid;//图片命名规则
			$upload->thumbMaxWidth = "1000,1000";
			$upload->thumbMaxHeight = "1000,1000";
			//$upload->maxSize  = C('M_MAX_UPLOAD');// 设置附件上传大小
			$upload->allowExts  =array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			$upload->savePath =C('M_UPLOAD_DIR').'UF/Uploads/pj/' ;// 设置附件上传目录
			if(!$upload->upload()) {// 上传错误提示错误信息
				$this->error($upload->getErrorMsg());
			}else{// 上传成功 获取上传文件信息
				$info =  $upload->getUploadFileInfo();
			}
			
			return $info;
		}
}
?>