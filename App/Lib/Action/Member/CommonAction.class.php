<?php
// 本类由系统自动生成，仅供测试用途
class CommonAction extends MCommonAction {
	var $notneedlogin=true;
    public function index(){
		$this->display();
    }
	
    public function login(){
		$loginconfig = FS("Webconfig/loginconfig");//判断快捷登录是否开启
		$this->assign("loginconfig",$loginconfig);
		//print_r($_GET['frm']);
		$this->assign("loginfrm",$_GET['frm']);//将login的来源位置传过去
		$this->display();
    }
	  public function registered(){
		
		$this->display();
    }
	
   public function regist(){
		$loginconfig = FS("Webconfig/loginconfig");//判断快捷登录是否开启
		$this->assign("loginconfig",$loginconfig);
		//PC端推广和手机端推广
		if($_GET['invite']){
			$user_name = M('members')->field("user_name")->where("id = {$_GET['invite']}")->find();
			session("tmp_invite_user",$_GET['invite']);
		}elseif($_SESSION['invite']){
			$user_name = M('members')->field("user_name")->where("id = {$_SESSION['invite']}")->find();
			session("tmp_invite_user",$_SESSION['invite']);		
		}
		$this->assign("user_name",$user_name['user_name']);
		$this->display();
		
    }
	
	
	private function actlogin_bak(){
		(false!==strpos($_POST['sUserName'],"@"))?$data['user_email'] = text($_POST['sUserName']):$data['user_name'] = text($_POST['sUserName']);
		$vo = M('members')->field('id,user_name,user_email,user_pass')->where($data)->find();
		if($vo){
			$this->_memberlogin($vo['id']);
			ajaxmsg();
		}else{
			ajaxmsg("用户名不存在",0);	
		}
	}
	
	//实现首页登录
	
		public function actloginshouye(){
		setcookie('LoginCookie','',time()-10*60,"/");
		//uc登陆
		
		$loginconfig = FS("Webconfig/loginconfig"); 
		$uc_mcfg  = $loginconfig['uc'];
		if($uc_mcfg['enable']==1){
			require_once C('APP_ROOT')."Lib/Uc/config.inc.php";
			require C('APP_ROOT')."Lib/Uc/uc_client/client.php";
		}
		
		//uc登陆
//		if($_SESSION['verify'] != md5(strtolower($_POST['sVerCode']))) 
//		{
//			ajaxmsg("验证码错误!",0);
//		}
		
		(false!==strpos($_POST['sUserName'],"@"))?$data['user_email'] = text($_POST['sUserName']):$data['user_name'] = text($_POST['sUserName']);
		$vo = M('members')->field('id,user_name,user_email,user_pass,is_ban')->where($data)->find();
		if($vo['is_ban']==1) ajaxmsg("您的帐户已被冻结，请联系客服处理！",0);
		
		if(!is_array($vo)){
			//本站登陆不成功，偿试uc登陆及注册本站
			if($uc_mcfg['enable']==1){
				list($uid, $username, $password, $email) = uc_user_login(text($_POST['sUserName']), text($_POST['sPassword']));
				if($uid > 0) {
					$regdata['txtUser'] = text($_POST['sUserName']);
					$regdata['txtPwd'] = text($_POST['sPassword']);
					$regdata['txtEmail'] = $email;
					$newuid = $this->ucreguser($regdata);
					                    
					if(is_numeric($newuid)&&$newuid>0){
						$logincookie = uc_user_synlogin($uid);//UC同步登陆
						setcookie('LoginCookie',$logincookie,time()+10*60,"/");
						$this->_memberlogin($newuid);
						ajaxmsg();//登陆成功
						
				/*		isset($_POST['remember']){
							setcookie('remember,$_POST['sUserName'],time()+7*24*3600');
						}else{
							setcookie('remember,$_POST['sUserName'],time()+0');}*/
			
					}else{
						ajaxmsg($newuid,0);
					}
				}
			}
			//本站登陆不成功，偿试uc登陆及注册本站
			ajaxmsg("用户名或密码错误！",0);
		}else{
			       
			if($vo['user_pass'] == md5($_POST['sPassword']) ){//本站登陆成功，uc登陆及注册UC
				//uc登陆及注册UC
				if($uc_mcfg['enable']==1){
					$dataUC = uc_get_user($vo['user_name']);
					if($dataUC[0] > 0) {
						$logincookie = uc_user_synlogin($dataUC[0]);//UC同步登陆
						setcookie('LoginCookie',$logincookie,time()+10*60,"/");
					}else{
						$uid = uc_user_register($vo['user_name'], $_POST['sPassword'], $vo['user_email']);
						if($uid>0){
							$logincookie = uc_user_synlogin($dataUC[0]);//UC同步登陆
							setcookie('LoginCookie',$logincookie,time()+10*60,"/");
						}
					}
				}
				//uc登陆及注册UC
				$this->_memberlogin($vo['id']);
				ajaxmsg();
			}else{//本站登陆不成功
				ajaxmsg("用户名或者密码错误！",0);
			}

		}
	}
	//首页登录结束
	
	


	public function actlogin(){
		setcookie('LoginCookie','',time()-10*60,"/");
		//uc登陆
		
		$loginconfig = FS("Webconfig/loginconfig"); 
		$uc_mcfg  = $loginconfig['uc'];
		if($uc_mcfg['enable']==1){
			require_once C('APP_ROOT')."Lib/Uc/config.inc.php";
			require C('APP_ROOT')."Lib/Uc/uc_client/client.php";
		}
		
		//uc登陆
		if($_SESSION['verify'] != md5(strtolower($_POST['sVerCode']))) 
		{
			ajaxmsg("验证码错误!",0);
		}
		
		$map['user_name|user_phone'] = text($_POST['sUserName']);                  
		$vo = M('members')->field('id,user_name,user_phone,user_pass,is_ban')->where($map)->find();
		if($vo['is_ban']==1) ajaxmsg("您的帐户已被冻结，请联系客服处理！",0);
//		if($vo['is_ban']==2) ajaxmsg("您的帐户已被冻结，请联系客服处理！",0);
//                读取网站登录次数设置
		$lnum =M('global')->where("code='login_num'")->getField('text'); 
                                         $login_num= intval($lnum);
                                         if($login_num=='0'){        //登录错误次数不受限制
                                             $this->loginlog($vo,0,0);
                                         }else{
                                                  $time = strtotime(date("Y-m-d",time()));
                                                  $where = ' and add_time >'.$time.' and add_time<'.($time+3600*24);
                                                  $fail_login_num =M('member_login')-> where("is_success=1 and uid={$vo['id']}{$where}")->count();
                                                  $ttime =$login_num-1-$fail_login_num;
                                                  if($fail_login_num>$login_num) {
                                                      ajaxmsg("账号密码错误，您还可以登录{$ttime}次",0);  exit;       
                                                  }elseif($fail_login_num==$login_num) {
                                                      $Mdata['is_ban'] = '2';
                                                      M('members')->where('id='.$vo['id'])->save($Mdata);
                                                      ajaxmsg("账号密码错误，今天登录次数超限，请明天再登陆",0); exit;   
                                                  }else{
                                                       $this->loginlog($vo,1,$ttime); 
                                                  }
                                       }           
		
	}
        /**
         * 
         * @param void $vo  登录信息
         * @param int $type  是否开启登录次数限制 0为不限制，1为限制
         */
        protected function loginlog($vo,$type,$ttime){
            $loginconfig = FS("Webconfig/loginconfig"); 
            $uc_mcfg  = $loginconfig['uc'];
            if($uc_mcfg['enable']==1){
                    require_once C('APP_ROOT')."Lib/Uc/config.inc.php";
                    require C('APP_ROOT')."Lib/Uc/uc_client/client.php";
            }
                if(!is_array($vo)){
                      //本站登陆不成功，偿试uc登陆及注册本站
                      if($uc_mcfg['enable']==1){
                              list($uid, $username, $password, $email) = uc_user_login(text($_POST['sUserName']), text($_POST['sPassword']));
                              if($uid > 0) {
                                      $regdata['txtUser'] = text($_POST['sUserName']);
                                      $regdata['txtPwd'] = text($_POST['sPassword']);
                                      $regdata['txtEmail'] = $email;
                                      $newuid = $this->ucreguser($regdata);

                                      if(is_numeric($newuid)&&$newuid>0){
                                              $logincookie = uc_user_synlogin($uid);//UC同步登陆
                                              setcookie('LoginCookie',$logincookie,time()+10*60,"/");
                                              $this->_memberlogin($newuid,0);
                                              ajaxmsg();//登陆成功
                                      }else{
                                              ajaxmsg($newuid,0);
                                      }
                              }  else {
                                   ajaxmsg("用户名或者密码错误！",0); 
                              }
                      }else{
                          //本站登陆不成功，偿试uc登陆及注册本站
                           ajaxmsg("用户名或者密码错误！",0); exit;  
                      }
              }else{

                      if($vo['user_pass'] == md5($_POST['sPassword']) ){//本站登陆成功，uc登陆及注册UC
                              //uc登陆及注册UC
                              if($uc_mcfg['enable']==1){
                                      $dataUC = uc_get_user($vo['user_name']);
                                      if($dataUC[0] > 0) {
                                              $logincookie = uc_user_synlogin($dataUC[0]);//UC同步登陆
                                              setcookie('LoginCookie',$logincookie,time()+10*60,"/");
                                      }else{
                                              $uid = uc_user_register($vo['user_name'], $_POST['sPassword'], $vo['user_email']);
                                              if($uid>0){
                                                      $logincookie = uc_user_synlogin($dataUC[0]);//UC同步登陆
                                                      setcookie('LoginCookie',$logincookie,time()+10*60,"/");
                                              }
                                      }
                              }
                              //uc登陆及注册UC
                              $this->_memberlogin($vo['id'],0);
                              ajaxmsg();
                      }else{//本站登陆不成功
                           $this->_memberlogin($vo['id'],1);
                          if($type=='1'){
                                if($ttime<='0'){
                                     $data['is_ban'] = '2';
                                      M('members')->where('id='.$vo['id'])->save($data);
                                    ajaxmsg("账号密码错误，今天登录次数超限，请明天再登陆",0);    
                                }else{
                                    ajaxmsg("用户名或者密码错误,您还可以登录{$ttime}次",0);    
                                }   
                          }else{
                                 ajaxmsg("用户名或者密码错误！",0); 
                          }  
                      }
              }
        }
	public function actlogout(){
		$this->_memberloginout();
		//uc登陆
		$loginconfig = FS("Webconfig/loginconfig");
		$uc_mcfg  = $loginconfig['uc'];
		if($uc_mcfg['enable']==1){
			require_once C('APP_ROOT')."Lib/Uc/config.inc.php";
			require C('APP_ROOT')."Lib/Uc/uc_client/client.php";
			$logout = uc_user_synlogout();
		}
		//uc登陆
		$this->assign("uclogout",de_xie($logout));
		$this->success("注销成功",__APP__."/");
	}
	
	private function ucreguser($reg){
		$data['user_name'] = text($reg['txtUser']);
		$data['user_pass'] = md5($reg['txtPwd']);
		$data['user_phone'] = text($reg['txtphone']);
		$count = M('members')->where("user_email = '{$data['user_phone']}' OR user_name='{$data['user_name']}'")->count('id');
		if($count>0) return "登陆失败,UC用户名冲突,用户名或者邮件已经有人使用";
		$data['reg_time'] = time();
		$data['reg_ip'] = get_client_ip();
		$data['last_log_time'] = time();
		$data['last_log_ip'] = get_client_ip();
		$newid = M('members')->add($data);
		
		if($newid){
			session('u_id',$newid);
			session('u_user_name',$data['user_name']);
			return $newid;
		}
		return "登陆失败,UC用户名冲突";
	}
	

	public function regaction(){
		$data['user_phone'] = text($_POST['txtphone']);	
		$data['user_name'] = text($_POST['txtUser']);
		$data['user_pass'] = md5($_POST['txtPwd']);
		$verify = $_POST['txtCode'];
		$Rec = $_POST['txtRec'];
		$ruser = $data['user_name'];
		$Retemp1 = M('members')->field("id")->where("user_name = '{$ruser}'")->find();
		if ($Retemp1['id'] > 0) {
			$this->error("用户名已经存在");
		}
		$count= M('members')->where("user_phone={$data['user_phone']} or user_name={$data['user_phone']}")->count();
		if($count > 0) $this->error("手机号码已经存在");
		if( !is_verify("",$verify,1,10*60) ) $this->error("短信验证码错误或者已失效");
		if ($Rec){
				$recommend = M('members')->where("user_name = '{$Rec}' or user_phone = '{$Rec}'")->find();
				if($recommend)	
					$data['recommend_id']=$recommend['id'];
				else $this->error("找不到该推荐人,请确认后输入");
			}
		//uc注册
		
		$data['reg_time'] = time();
		$data['reg_ip'] = get_client_ip();
		$data['last_log_time'] = time();
        $data['last_log_ip'] = get_client_ip();
		
		$newid = M('members')->add($data);		
		if($newid){
			$updata['uid']=$newid;
			$updata['phone_status']=1;
			$updata['phone_credits']=10;
			M('members_status')->add($updata);
			$updata1['uid']=$newid;
			$updata1['cell_phone']=$data['user_phone'];
			M('member_info')->add($updata1);
			session('u_id',$newid);
			session('u_user_name',$data['user_name']);
			$this->success("注册成功",__APP__."/member");
		}else{
			$this->error("注册失败！");
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
					$imgCode=$_POST['imgCode'];
					(!$imgCode||($_SESSION['verify']!=md5(strtolower($imgCode))))&&ajaxmsg("图形验证码错误!",0);
					(!$phone)&&ajaxmsg("请输入手机号",0);
					
					if(!!M('members')->getFieldByUserPhone($phone,'id')) 
						ajaxmsg("该手机号已被注册",2);
					/*$ip=get_client_ip();
					if(!!$ip){
						$map['ip']=$ip;
						$map['ukey']=$phone;
						$map['_logic'] = 'OR';
						M('verify')->where($map)->count()>4&&ajaxmsg("",0);
					}*/
					$code = rand_string($phone,6,1,2);//用户注册时无id,用手机号代替id
					$res=sendsms($phone,array($code),23229);
					if($res){
						session("temp_phone",$phone);
						ajaxmsg("",1);
					}
					else ajaxmsg("",0);
					
				break;
				//登录密码
				case 1:
					$xuid = M('members')->getFieldByUserPhone($phone,'id');
					if(!$xuid) ajaxmsg("",2);
					
					$code = rand_string($xuid,6,1,2);//用户注册时无id,用手机号代替id
					
					
					$res=sendsms($phone,array($code),23229);
						
					
					if($res){
						session("temp_phone",$phone);
						ajaxmsg("",1);
					}
					else ajaxmsg("",0);
				break;
				//支付密码
				case 2:
					$user= M('members')->find($this->uid);				
					$code = rand_string($this->uid,6,1,2);
					
					
					$res=sendsms($user['user_phone'],array($code),23229);
						
					
					if($res){
						session("temp_phone",$phone);
						ajaxmsg("",1);
					}
					else ajaxmsg("",0);
				break;
			}
		

		}
	public function validatephone() {
		if (session('code_temp')==text($_POST['code'])) {
			$updata['phone_status'] = 1;
			if (!session("temp_phone")) {
				ajaxmsg("验证失败", 0);
			}
            $mid = $this->regaction();
			
			$newid = setMemberStatus($mid, 'phone', 1, 10, '手机');
			if ($newid) {
				ajaxmsg();
			} else{
				ajaxmsg("验证失败", 0);
			}
		} else {
			$this->regaction();
			ajaxmsg("验证校验码不对，请重新输入！", 2);
		} 
	} 
	
	public function emailverify(){
		$code = text($_GET['vcode']);
		$uk = is_verify(0,$code,1,60*1000);
		if(false===$uk){
			$this->error("验证失败");
		}else{
			$this->assign("waitSecond",3);
            setMemberStatus($uk, 'email', 1, 9, '邮箱');  
			$this->success("验证成功",__APP__."/member");
		}
	}
	
	public function getpasswordverify(){
		$code = text($_GET['vcode']);
		$uk = is_verify(0,$code,7,60*1000);
		if(false===$uk){
			$this->error("验证失败");
		}else{
			session("temp_get_pass_uid",$uk);
			$this->display('getpass');
		}
	}
	
	public function setnewpass(){
		$d['content'] = $this->fetch();
		echo json_encode($d);
	}
	
	public function dosetnewpass(){
		$per = C('DB_PREFIX');
		$uid = session("temp_get_pass_uid");
		$oldpass = M("members")->getFieldById($uid,'user_pass');
		if($oldpass == md5($_POST['pass'])){
			$newid = true;
		}else{
			$newid = M()->execute("update {$per}members set `user_pass`='".md5($_POST['pass'])."' where id={$uid}");
		}
		
		if($newid){
			session("temp_get_pass_uid",NULL);
			ajaxmsg();
		}else{
			ajaxmsg('',0);
		}
	}
	
	
	public function ckuser(){
		$map['user_name|user_phone'] = text($_POST['UserName']);
		
		if (M('members')->where($map)->count('id')) 
			ajaxmsg("",0);
        else ajaxmsg("",1);
        
	}
	
	public function ckphone(){
		$map['user_phone'] = text($_POST['phone']);
		$count = M('members')->where($map)->count('id');
        
		if ($count>0) {
			$json['status'] = 0;
			exit(json_encode($json));
        } else {
			$json['status'] = 1;
			exit(json_encode($json));
        }
	}
	public function emailvsend(){
		session('email_temp',text($_POST['email']));
		$mid = $this->regaction();
				
		$status=Notice(8,$mid);
		if($status) ajaxmsg('邮件已发送，请注意查收！',1);
		else ajaxmsg('邮件发送失败,请重试！',0);
		
    }
	public function ckcode(){
	   
		if($_SESSION['verify'] != md5($_POST['sVerCode'])) {
			echo (0);
		 }else{
			echo (1);
        }
	}
	
	public function verify(){
	     ob_clean();
		import("ORG.Util.Image");
		Image::buildImageVerify();
	}
	
	public function regsuccess(){
		$this->assign('userEmail',M('members')->getFieldById($this->uid,'user_email'));
		$d['content'] = $this->fetch();
		echo json_encode($d);
	}


	public function getpassword(){
		$d['content'] = $this->fetch();
		echo json_encode($d);
	}

	// public function dogetpass(){
		// (false!==strpos($_POST['u'],"@"))?$data['user_email'] = text($_POST['u']):$data['user_name'] = text($_POST['u']);
		// $vo = M('members')->field('id')->where($data)->find();
		// if(is_array($vo)){
			// $res = Notice(7,$vo['id']);
			// if($res) ajaxmsg();
			// else ajaxmsg('',0);
		// }else{
			// ajaxmsg('',0);
		// }
	// }
	
	//修改密码
		public function changepw(){
			$phone= text($_POST['phone']);
			if(!$phone)
				ajaxmsg("请填写手机号！",0);
			$xuid = M('members')->getFieldByUserPhone($phone,'id');
			if(!$xuid)
				ajaxmsg("手机号未注册！",0);
		    $code = text($_POST['verify']);
		    if( is_verify($xuid,$code,1,10*60) ){
				$user=M('Members')->find($xuid);
		        $user['user_pass']=md5(text($_POST['password']));
		        $new= M("Members")->save($user);
		       
		        if(false!==$new) {
		            ajaxmsg("登录密码修改成功",1);
		        }else{
					ajaxmsg("登录密码更新失败！",0);
				}
		    }else{
		        ajaxmsg("验证码错误！",0);
		    }
		
		}
	
    public function register2(){
		$this->display();
	}
	public function phone(){
		$this->assign("phone",$_GET['phone']);
		$data['content'] = $this->fetch();
		exit(json_encode($data));
		
	}
	
	//跳过手机验证
	public function skipphone(){
		$this->regaction();
		ajaxmsg();
		
	}
	//推荐人检测
	public function ckInviteUser(){
		$Rec = text($_POST['InviteUserName']);
		if ($Rec){
				$recommend = M('members')->where("user_name = '{$Rec}' or user_phone = '{$Rec}'")->count("id");
			}
        
		if ($recommend==1) {
			$json['status'] = 1;
			exit(json_encode($json));
        } else {
			$json['status'] = 0;
			exit(json_encode($json));
        }
	}
}