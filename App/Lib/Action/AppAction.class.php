<?php
    /**
    * 手机版用户中心公共类
    */
    class AppAction extends Action
    {
    	var $savePathNew=NULL;
	var $thumbMaxWidthNew="10,50";
	var $thumbMaxHeightNew="10,50";
	var $thumbNew=NULL;
	var $allowExtsNew=NULL;
	var $saveRule=NULL;
        public $uid;
        public $uname;
        public $glo;
        var $pre =NULL;
        
        function _initialize(){
        	header("Access-Control-Allow-Origin: *");
			$datag = get_global_setting();
			$this->glo = $datag;//供PHP里面使用
			$this->assign("glo",$datag);//公共参数
        	$this->pre =C('DB_PREFIX');

			if($this->needlogin){
				$uid=intval($_POST['uid']);
			//$uid=intval($_GET['uid']);
				if(!$uid)
					ajaxmsg("请先登录",1);
				if(!M('Members')->find($uid))
					ajaxmsg("用户不存在",1);
				$this->uid=$uid;
			}

        }
		public function sendphone(){
			
			$smsTxt = FS("Webconfig/smstxt");
			$smsTxt=de_xie($smsTxt);
			$phone = text($_POST['phone']);
			$type = text($_POST['type']);
			switch ($type){
				//注册
				case 0:
					$xuid = M('members')->getFieldByUserPhone($phone,'id');
					if($xuid>0 && $xuid<>$this->uid) ajaxmsg("该手机号已经注册",2);
					
					$code = rand_string($phone,6,1,2);//用户注册时无id,用手机号代替id
					
					
					$res=sendsms($phone,array($code),23229);
						
					
					if($res){
						session("temp_phone",$phone);
						ajaxmsg("短信发送成功",0);
					}
					else ajaxmsg("短信发送失败,请重试",1);
					
				break;
				//登录密码
				case 1:
					$xuid = M('members')->getFieldByUserPhone($phone,'id');
					if(!$xuid) ajaxmsg("该号码尚未注册",1);
					
					$code = rand_string($xuid,6,1,2);//用户注册时无id,用手机号代替id
					
					
					$res=sendsms($phone,array($code),23229);
						
					
					if($res){
						session("temp_phone",$phone);
						ajaxmsg("短信发送成功",0);
					}
					else ajaxmsg("短信发送失败,请重试",1);
				break;
				//支付密码
				case 2:
					$xuid = M('members')->getFieldByUserPhone($phone,'id');
					if(!$xuid) ajaxmsg("该号码尚未注册",1);
					if($xuid!=$this->uid) ajaxmsg("数据异常",1);		
					$code = rand_string($this->uid,6,1,2);
					
					
					$res=sendsms($phone,array($code),23229);
						
					
					if($res){
						session("temp_phone",$phone);
						ajaxmsg("短信发送成功",0);
					}
					else ajaxmsg("短信发送失败,请重试",1);
				break;
			}
		
		}

		//上传图片
	function CUpload(){
		if(!empty($_FILES)){
			return $this->_Upload();
		}
	}

	function _Upload(){
		import("ORG.Net.UploadFile");
        $upload = new UploadFile();
		
		$upload->thumb = true;
		$upload->saveRule = $this->saveRule;//图片命名规则
		$upload->thumbMaxWidth = $this->thumbMaxWidth;
		$upload->thumbMaxHeight = $this->thumbMaxHeight;
		$upload->maxSize  = C('App_MAX_UPLOAD') ;// 设置附件上传大小
		$upload->allowExts  = C('ADMIN_ALLOW_EXTS');// 设置附件上传类型
		$upload->savePath =  $this->savePathNew?$this->savePathNew:C('ADMIN_UPLOAD_DIR');// 设置附件上传目录
   

      if(!$upload->upload()) {// 上传错误提示错误信息
         $info['status']=0;
         $info['info']=$upload->getErrorMsg();
      }else{// 上传成功 获取上传文件信息
         $info['status']=1;
         $info['info']=$upload->getUploadFileInfo();
      }

   
		return $info;
	}
	//上传图片END
		
    }
?>
