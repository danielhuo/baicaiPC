<?php
    /**
    * 手机版 用户中心
    */
    class UserAction extends MobileAction
    {
        
         public function index()
         {   
             $this->display();
         }
         
         /**
         * 个人资料
         */
         public function info()
         {
             $this->assign("kflist",get_admin_name());
             $pre = C('DB_PREFIX');
             $rule = M('ausers u')->field('u.id,u.qq,u.phone')->join("{$pre}members m ON m.customer_id=u.id")->where("u.is_kf =1 and m.customer_id={$minfo['customer_id']}")->select();
             foreach($rule as $key=>$v){
                $list[$key]['qq']=$v['qq'];
                $list[$key]['phone']=$v['phone'];
             }
             $this->assign("kfs",$list);
        
             $minfo =getMinfo($this->uid,true);

             $this->assign("minfo",$minfo);
             $this->display();
         }
         
         /**
         *推广明细
         */
        public function promotionlist(){       
            // $field= "m.id,m.user_name,m.reg_time,sum(ml.affect_money) award";
            // $friends= M("members m")->field($field)->join(" lzh_member_moneylog ml ON m.id = ml.target_uid ")->where(" m.recommend_id ={$this->uid} AND ml.type =13")->group("ml.target_uid")->select();
            // foreach ($friends as $key => $value) {
            //     $friends[$key]['reg_time']=date("Y/m/d",$value['reg_time']);
            // }
          //  $data['friends']=$friends;     
            $promote=promote_data($this->uid); 
        //    dump($promote['detail']);  
            $this->assign("data",$promote['detail']);
            $this->display();
         }
         /**
         *推广统计
         */


         public function promotionAnalytic(){
            $promote=promote_data($this->uid); 
            $this->assign("data",$promote['total']);
            $this->display();

         }
             
         
         /**
         * 安全中心
         */
         public function safe()
         {	
			 $mstatus=M('members_status')->field(true)->find($this->uid);
			 $id_status=$mstatus['id_status'];
             $this->assign("memberinfo", M('members')->find($this->uid));
             $this->assign("mstatus",$mstatus); 
			 $this->assign("id_status",$id_status);
             $this->assign("memberdetail", M('member_info')->find($this->uid));
             $paypass = M("members")->field('pin_pass')->where('id='.$this->uid)->find();
             $this->assign('paypass', $paypass['pin_pass']);
             $this->display();
         }
        /**
		*修改用户名	
		*/
		public function changename(){
			$map['user_name'] = text($_POST['name']);
			$count = M('members')->where($map)->count('id');
			$count1 = M('members')->where("user_phone = {$map['user_name']}")->count('id');
			$str= strlen($map['user_name']);
			if ($map['user_name'] == "") ajaxmsg("用户名不能为空！",1);
			if($str<6) ajaxmsg("用户名不能小于六位！",1);
			if( $count1>0 or $count >0 ) ajaxmsg("用户名已被使用！",1);
			$newid = M('members')->where("id = {$this->uid}")->save($map);
			if ($newid){
				session('u_user_name', $map['user_name']);
				ajaxmsg($map['user_name'],0);
			}else{
				ajaxmsg("提交失败！",1);
			}
		 }
		 
		
         /**
         * 设置支付密码
         */
         public function setPayPass()
         {
            if($this->isAjax()){
                $password = $this->_post('password');
                $paypass = $this->_post('paypass');
                $paypass2 = $this->_post('paypass2');
                if(!$password || !$paypass || !$paypass2){
                    die('数据不完整，请检查后再试');
                }
                $paypass == $password && die('不能和登陆密码相同，请重新输入');
                $paypass != $paypass2 && die('两次支付密码不一致，请重新输入');
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
                
            }else{
                $this->display();
            } 
         }
         /**
         * 修改支付密码
         * 
         */
         public function editpaypass()
         {   
             if($this->isAjax()){
                $oldpass = $this->_post('oldpass');
                $paypass = $this->_post('paypass');
                $paypass2 = $this->_post('paypass2');
                if(!$oldpass || !$paypass || !$paypass2){
                    die('数据不完整，请检查后再试');
                }
                $paypass == $oldpass && die('新密码不能和旧密码相同，请重新输入');
                $paypass != $paypass2 && die('两次支付密码不一致，请重新输入');
                $user = M('members')->field('pin_pass')->where('id='.$this->uid)->find();
                !$user  && die('数据有误');
                if($user['pin_pass']!=md5($oldpass)){
                    die('支付密码不正确');   
                }
                if(M("members")->where('id='.$this->uid)->save(array('pin_pass'=>md5($paypass)))){
                    die('TRUE');
                }else{
                    echo '设置出错，刷新页面重试';   
                } 
                 
             }else{
                $user=M('Members')->find($this->uid);
                $this->user=$user;
                $this->display(); 
             }
         }
         public function changepin(){
             $data['pin_pass']=md5(text($_POST['password']));
             $verify = text($_POST['verify']);
             $user= M('members')->find($this->uid);    
             if( is_verify($this->uid,$verify,1,10*60) ){
                 $new= M("members")->where("id={$this->uid}")->save($data);
                 if(false!==$new) ajaxmsg("",1);
             }else{
                 ajaxmsg("验证码错误！",0);
             }
         
         }
         /**
         * 修改登录密码
         * 
         */
         public function editpass()
         {
             if($this->isAjax()){
                $oldpass = $this->_post('oldpass');
                $password = $this->_post('password');
                $password2 = $this->_post('password2');
                if(!$oldpass || !$password || !$password2){
                    die('数据不完整，请检查后再试');
                }
                $password == $oldpass && die('新密码不能和旧密码相同，请重新输入');
                $password != $password2 && die('两次密码不一致，请重新输入');
                $user = M('members')->field('user_pass')->where('id='.$this->uid)->find();
                !$user  && die('数据有误');
                if($user['user_pass']!=md5($oldpass)){
                    die('旧密码不正确');   
                }
                if(M("members")->where('id='.$this->uid)->save(array('user_pass'=>md5($password)))){
                    die('TRUE');
                }else{
                    echo '设置出错，刷新页面重试';   
                } 
                 
             }else{
                $this->display(); 
             }
         }
         
         public function msg()
         {
             if($this->isAjax()){
                $id = $this->_get('id');
                $msg = M('inner_msg')->field('msg')->where('id='.$id.' and uid='.$this->uid)->find();
                if(count($msg)){
                    M('inner_msg')->where('id='.$id)->save(array('status'=>1));
                    echo $msg['msg'];
                }else{
                    echo '<font color=\'red\'>读取错误</font>';
                }

             }else{
                $map['uid'] = $this->uid;
                //分页处理
                import("ORG.Util.Page");
                $count = M('inner_msg')->where($map)->count('id');
                $p = new Page($count, 15);
                $page = $p->show();
                $Lsql = "{$p->firstRow},{$p->listRows}";
                //分页处理
                $list = M('inner_msg')->where($map)->order('status asc,id DESC')->limit($Lsql)->select();

                $this->assign("list",$list);
                $this->assign("pagebar",$page);
                $this->assign("count",$count);
                $this->display();     
             }
                
         }
		 public function promote()
         {       
				
                $this->display();                
         }
		public function idcard(){
			$ids = M('members_status')->getFieldByUid($this->uid,'id_status');
			if($ids==1){
				$vo = M("member_info")->field('idcard,real_name,card_img,card_back_img,card_ren_img')->find($this->uid);
				$this->assign("vo",$vo);
			}
			$this->assign("id_status",$ids);
			$this->display();
		
		}
		public function saveid(){		
			$uid=$this->uid;
			$data['real_name']=$_POST['realname'];
			$data['idcard'] = $_POST['idcard'];
			$data['up_time'] = time();
			/////////////////////////保存name_apply
			$data1['idcard'] = text($_POST['idcard']);
			$data1['up_time'] = time();
			$data1['uid'] = $uid;
			$data1['status'] =1;
			$b = M('name_apply')->where("uid = {$uid}")->count('uid');
			if($b==1){
				$id	= M('name_apply')->where("uid ={$uid}")->save($data1);
			}else{
				$id	= M('name_apply')->add($data1);
			}
			////////////////////////保存member_info
			
			if(empty($data['real_name'])||empty($data['idcard']))  
				$this->error("请填写真实姓名和身份证号码");
			$c = M('member_info')->where("uid = {$uid}")->count('uid');
			if($c==1){
				$newid = M('member_info')->where("uid = {$uid}")->save($data);
			}else{
				$data['uid'] = $uid;
				$newid = M('member_info')->add($data);
			}
			////保存member_status
			$data2['id_status'] =1;
			$d = M('members_status')->where("uid = {$uid}")->count('uid');
			if($d == 1){
				$newid1= M('members_status')->where("uid = {$uid}")->save($data2);	
			}else{
				$data2['uid']= $uid;
				$newid1= M('members_status')->add($data2);
			}
			
			if($id!==false && $newid!==false&& $newid1!==false) {
				$this->success("恭喜您认证通过！",__APP__."/M/user/idcard");}
			else{ $this->error("提交失败！");}
		}
		public function validId(){
			$map['idcard']=text($_POST['idcard']);
			$id = M('member_info')->where($map)->count();
			if($id!=0) ajaxmsg('',1);
			else ajaxmsg('',0);		
		}
		public function loan()
         {
            $map['loan_uid']=$this->uid;
            $parm['map'] = $map;
            $parm['pagesize'] = 5;
            $sort = "desc";
            $parm['orderby']="l.status ASC,l.id DESC";
            $list = getLoanList($parm);
			
            if($this->isAjax()){
                $string ='';
                foreach($list['list'] as $vb){
				
                    
					$string .= '
                        <table >
				<tr>
					<td colspan="2" class="title1">
                            <a href="'.U('m/loaninvest/detail', array('id'=>$vb['id'])).'" >JK'.$vb['id'].cnsubstr($vb['loan_name'],17).'</a>
                    </td>
					<td colspan="2" class="title1" >
					    
						<canvas class="proces" width=40 height=40 value="'.$vb['progress'].'"></canvas>
					</td>
				</tr>
				<tr>
					<th>配资金额:</th><td class="amount">'.getMoneyFormt($vb['loan_amount']).'</td>
					<th>还款期限:</th><td>'.$vb['loan_duration']; 
                              $string .= $vb['duration_type']==1?'个月':'天';    
                              
                                $string .='</td>		
				</tr>
				<tr>
					<th>年　利率:</th>
					<td>'.$vb['interest_rate'].'%</td>
					<th>还需资金:</th>
					<td class="amount">'.getMoneyFormt($vb['need']).'</td>		
				</tr>
				<tr>
					<th>年　费率:</th>
					<td>'.$vb['fee_rate'].'%</td>
					<th>借款状态:</th>
					<td>'.$vb['status2'].'</td>	
				</tr>
			</table>';
						
                                
                }
                echo $string;
            }else{
                
                
        
                $this->assign('list', $list);
                $this->assign('Lconfig', $Lconfig);
                $this->display(); 
            }  
         }
		 public function loaninvest()
         {
            $map['li.invest_uid']=$this->uid;
            $parm['map'] = $map;
            $parm['pagesize'] =15;
            $sort = "desc";
            $parm['orderby']="l.status ASC,li.id DESC";
            $list = getLoaninvestList($parm);
			
            if($this->isAjax()){
                echo json_encode($list['list']);
            }else{
       
                $this->assign('list', $list);
                $this->assign('Lconfig', $Lconfig);
                $this->display(); 
            }  
         }
    }
?>
