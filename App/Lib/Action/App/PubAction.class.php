<?php
    class PubAction extends AppAction
    {
		var $notneedlogin=true;
		public function Verify()
		{
			import("ORG.Util.Image");            
			Image::buildImageVerify();   
		}
		/**
		* 用户登陆
		*/
		public function login()
		{   
			
            if(!$this->isPost()) ajaxmsg("提交出错",1);
            	
			$user_name = $_POST['username'];
			$pass = $_POST['password'];
			if(!$user_name||!$pass)
				ajaxmsg("数据不完整",1);

			$map['user_name|user_phone'] = $user_name;
			$vo = M('members')->field("id,user_name,user_pass")->where($map)->find();
			if(!$vo||($vo['user_pass']!=md5($pass))){
				ajaxmsg("用户名或密码错误",1);
			}
			$user['uid']=$vo['id'];
			$user['uname']=$vo['user_name'];
			$user['user_head']=get_avatar($vo['id']);
			$result['user']=$user;
            ajaxmsg($result,0);
             
         }
         /**
         * 注销用户
         */
         public function Logout()
         {
            session(null);
            $this->success('安全退出!',U('M/index/index'));   
         } 
         
		
		//修改密码
		public function changepw(){
			$phone= text($_POST['phone']);
			if(!$phone)
				ajaxmsg("请填写手机号！",1);
			$xuid = M('members')->getFieldByUserPhone($phone,'id');
			if(!$xuid)
				ajaxmsg("手机号未注册！",1);
		    $code = text($_POST['verify']);
		    if( is_verify($xuid,$code,1,10*60) ){
				$user=M('Members')->find($xuid);
		        $user['user_pass']=md5(text($_POST['password']));
		        $new= M("Members")->save($user);
		       
		        if(false!==$new) {
		           
		            session("u_id",$xuid);
		            session('u_user_name',$user['user_name']);
		            ajaxmsg("登录密码修改成功",0);
		        }else{
					ajaxmsg("登录密码更新失败！",1);
				}
		    }else{
		        ajaxmsg("验证码错误！",1);
		    }
		
		}
		
		public function yzphone(){
			$map['user_phone'] = text($_POST['phone']);
			$count = M('members')->where($map)->count('id');
			if($count>0 ) ajaxmsg("",1);
			ajaxmsg("",0);
			
		} 
		public function yzusername(){
			$map['user_name'] = text($_POST['username']);
			$count = M('members')->where($map)->count('id');
			$str= strlen($map['user_name']);
			if ($map['user_name'] == "") ajaxmsg("用户名不能为空！",1);
			if($str<6) ajaxmsg("用户名不能小于六位！",1);
			
			if($count>0 ) ajaxmsg("用户名已被使用！",1);
			ajaxmsg("",0);
		}
		
		public function regist(){
		    $phone = text($_POST['phone']);
		    $pwd   = text($_POST['password']);
		    $Rec   = text($_POST['txtRec']);
		    $verify= text($_POST['verify']);
		    if(!$phone|| !$pwd|| !$verify)
		         ajaxmsg("请提交完整信息",1);
		    
		    $count= M('members')->where("user_phone={$phone} or user_name={$phone}")->count();
		    if($count > 0) ajaxmsg("手机号码已注册！",1);
		    
		    if( !is_verify("",$verify,1,10*60) )
		        ajaxmsg("验证码错误或者已失效",1);      
			if ($Rec){
				$recommend = M('members')->where("user_name = '{$Rec}' or user_phone = '{$Rec}'")->find();
				if($recommend)	
					$data['recommend_id']=$recommend['id'];
				else ajaxmsg("找不到该推荐人,请确认后输入",1);
			}
			$data['user_name']=$phone;
			$data['user_phone']=$phone;
			$data['user_pass']=md5($pwd);
			$data['reg_time'] = time();
			$data['reg_ip'] = get_client_ip();
			$data['last_log_time'] = time();
			$data['last_log_ip'] = get_client_ip();
			
			if($newid = M("members")->add($data)){
			    $updata['uid']=$newid;
			    $updata['phone_status']=1;
			    $updata['phone_credits']=10;
			    M('members_status')->add($updata);
			    $updata1['uid']=$newid;
			    $updata1['cell_phone']=$phone;
			    M('member_info')->add($updata1);	    
			    ajaxmsg($newid,0);
			}else{
			    ajaxmsg("注册失败",0);
			}
		
		}
    }
?>
