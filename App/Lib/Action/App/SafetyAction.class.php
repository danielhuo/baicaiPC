<?php
    /**
    * 手机版 用户中心
    */
    class SafetyAction extends AppAction{
        var $needlogin=true;
        public function index()
        {   
             
            $minfo=M('Members m')
            ->join("{$this->pre}member_info mi on m.id=mi.uid")
            ->join("{$this->pre}members_status ms on m.id=ms.uid")
            ->field("mi.uid,ms.id_status,mi.real_name,m.user_phone,m.user_email,m.pin_pass")
            ->where("m.id={$this->uid}")
            ->find();
            if ($minfo['id_status']==0) {
                $minfo['real_name']="未认证";
            }elseif ($minfo['id_status']==3) {
                $minfo['real_name']="审核中";
            }
            $minfo['real_name']=$minfo['real_name']=="未认证"?"未认证":hidecard($minfo['real_name'],6);
            $minfo['user_phone']=$minfo['user_phone']?hidecard($minfo['user_phone'],2):"未认证";
            $minfo['user_email']=$minfo['user_email']?hidecard($minfo['user_email'],2):"未认证";
            $minfo['pin_pass']=$minfo['pin_pass']?"已设置":"未设置";
            $result['minfo']=$minfo;
            ajaxmsg($result,0);

        }
        
         /**
         * 设置支付密码
         */
         public function setPayPass()
         {
            
            $password = $this->_post('password');
            $paypass = $this->_post('paypass');
            $paypass2 = $this->_post('paypass2');
            (!$password || !$paypass || !$paypass2)&&ajaxmsg('数据不完整，请检查后再试',1);
            
            $paypass == $password &&ajaxmsg('不能和登陆密码相同，请重新输入',1);
            $paypass != $paypass2 &&ajaxmsg('两次支付密码不一致，请重新输入',1);
            $user = M('members')->field('user_pass, pin_pass')->where('id='.$this->uid)->find();
            !$user  && die('数据有误');
            if($user['user_pass']!=md5($password)){
                die('登陆密码不正确');   
            }
            if(M("members")->where('id='.$this->uid)->save(array('pin_pass'=>md5($paypass)))){
                die('TRUE');
            }else{
                echo '设置出错，刷新页面重试';   
            }
                
            
         }
         /**
         * 修改支付密码
         * 
         */
        public function changepaybyold(){
            $oldpay =$_POST['oldpay'];
            $newpay =$_POST['newpay'];
            (!$oldpay || !$newpay)&&ajaxmsg('数据不完整',1);
            $newpay == $oldpay && ajaxmsg('新密码不能和旧密码相同，请重新输入',1);
            (strlen($newpay)<6||strlen($newpay)>10)&&ajaxmsg("密码长度应为6-10位",1);
            $user = M('members')->field('pin_pass,user_pass')->where('id='.$this->uid)->find();
            $pin_pass=$user['pin_pass']?$user['pin_pass']:$user['user_pass'];
            ($pin_pass!=md5($oldpay))&&ajaxmsg("原支付密码不正确",1);   
            if(M("members")->where('id='.$this->uid)->save(array('pin_pass'=>md5($newpay))))
                ajaxmsg("修改成功",0);
            else
                ajaxmsg("修改失败",1);
        }
        public function changepaybyphone(){
            $pin_pass=text($_POST['newpay']);
            $verify = text($_POST['verify']);
            (!$pin_pass||!$verify)&&ajaxmsg("数据不完整",1);
            !is_verify($this->uid,$verify,1,10*60)&&ajaxmsg("验证码错误",1);
            $pin_pass=md5($pin_pass);   
            $user = M('members')->field('pin_pass,user_pass')->find($this->uid);
            ($pin_pass==$user['user_pass'])&&ajaxmsg("支付密码和登录密码不能相同",1); 
            ($pin_pass==$user['pin_pass'])&&ajaxmsg(" 新密码和原密码不能相同",1);     
            if(M("members")->where("id={$this->uid}")->setField("pin_pass",$pin_pass)) ajaxmsg("修改成功",0);
            else ajaxmsg("修改失败",1);
        }

         public function idcard(){
            $id_status = M('members_status')->getFieldByUid($this->uid,'id_status');
            if($id_status==1){
                $minfo= M("member_info")->field('idcard,real_name')->find($this->uid);
                $minfo['real_name']=hidecard($minfo['real_name'],6);
                $minfo['idcard']=hidecard($minfo['idcard'],1);
            }
            $minfo['id_status']=$id_status;
            $data['minfo']=$minfo;
            ajaxmsg($data,0);
        
        }
        public function saveid(){   
            $uid=$this->uid;    
            $real_name=$_POST['real_name'];
            $idcard=$_POST['idcard'];
            $id_status=M('members_status')->getFieldByUid($uid,"id_status");
            if($id_status==1) ajaxmsg("不能重复认证",1);
            if(empty($real_name)||empty($idcard))  ajaxmsg("请填写数据",1);
            if(M('member_info')->getFieldByIdcard($idcard,"uid")) ajaxmsg('身份证已被注册',1);
              
            ////////////////////////保存member_info
            $member_info['uid']=$uid;
            $member_info['real_name']=$real_name;
            $member_info['idcard'] = $idcard;
            $member_info['up_time'] = time();
            if(1==M('member_info')->where("uid = {$uid}")->count('uid'))
                $upinfo = M('member_info')->save($member_info);
            else
                $upinfo = M('member_info')->add($member_info);
            ////保存member_status
            $members_status['uid']=$uid;
            $members_status['id_status'] =1;
            if(1==M('members_status')->where("uid = {$uid}")->count('uid'))
                $upstatus= M('members_status')->save($members_status);  
            else
                $upstatus= M('members_status')->add($members_status);
            /////////////////////////保存name_apply
            $name_apply['idcard'] = $idcard;
            $name_apply['up_time'] = time();
            $name_apply['uid'] = $uid;
            $name_apply['status'] =1;
            $name_apply['deal_info'] ="用户提交,未经后台审核";
            if(1==M('name_apply')->where("uid = {$uid}")->count('uid')){
                $upapply = M('name_apply')->where("uid ={$uid}")->save($name_apply);
            }else{
                $upapply = M('name_apply')->add($name_apply);
            }

            if($upinfo!==false && $upstatus!==false&& $upapply!==false)
                ajaxmsg("提交成功",0);
            else ajaxmsg("提交出错",1);
        }
        
         /**
         * 修改登录密码
         * 
         */
         public function changepassbyold(){
            $oldpass =$_POST['oldpass'];
            $password = $_POST['password'];
            (!$oldpass || !$password)&&ajaxmsg('数据不完整，请检查后再试',1);
            ($oldpass==$password)&&ajaxmsg('新密码不能和旧密码相同',1);
            (strlen($password)<6||strlen($password)>10)&&ajaxmsg("密码长度应为6-10位",1);
            $user = M('members')->field('user_pass')->find($this->uid);
            ($user['user_pass']!=md5($oldpass))&&ajaxmsg('旧密码不正确',1);   
            if(M("members")->where('id='.$this->uid)->setField("user_pass",md5($password)))
               ajaxmsg("修改成功",0);
            else
                ajaxmsg("修改失败",1);

         }

         /**
         * 修改手机
         * 
         */
         public function changephone(){
            /*$newphone=text($_POST['verify']);
            $verify = text($_POST['verify']);
            (!$newphone||!$verify)&&ajaxmsg("数据不完整",1);
            !is_verify($this->uid,$verify,3,10*60)&&ajaxmsg("验证码错误",1);
            $pin_pass=md5($pin_pass);   
            $user = M('members')->field('pin_pass,user_pass')->find($this->uid);
            ($pin_pass==$user['user_pass'])&&ajaxmsg("支付密码和登录密码不能相同",1); 
            ($pin_pass==$user['pin_pass'])&&ajaxmsg(" 新密码和原密码不能相同",1);     
            if(M("members")->where("id={$this->uid}")->setField("pin_pass",$pin_pass)) ajaxmsg("修改成功",0);
            else ajaxmsg("修改失败",1);*/

         }  
		
    }
?>
