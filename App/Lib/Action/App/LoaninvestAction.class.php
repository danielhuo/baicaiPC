<?php

    class LoaninvestAction extends AppAction
    {
		public function index(){
				
            $maprow = array();
            $searchMap['status']=array("in",'2,4,6,7,8'); 
            $parm['map'] = $searchMap;
            $parm['pagesize'] =20;
            $sort = "desc";
            $parm['orderby']="l.status ASC,l.id DESC";
            $list = getLoanList($parm);
            if(($list['page']['nowPage']>$list['page']['total'])||!$list|!$list['list'])
            	ajaxmsg("加载失败",1);
            else ajaxmsg($list,0);	  
        }
		
        public function detail(){   
            $pre = C('DB_PREFIX');
            $id = intval($_GET['id']);
            $loan = M("loan")->where('id='.$id)->find();

			$loan['has_collect']=$loan['status']>=7?$loan['loan_amount']:$loan['has_collect'];
			$loan['need'] = $loan['loan_amount'] - $loan['has_collect'];
			$loan['progress'] = getFloatValue($loan['has_collect']/$loan['loan_amount']*100,2);
			
            $loan['lefttime'] = $loan['collect_time']- time(); 
            $memberinfo = M("members")
                            ->field("id,customer_name,customer_id,user_name,reg_time,credits")
                            ->where("id={$loan['uid']}")
                            ->find();
			
			$parm['map']="l.loan_id={$id}";
			$parm['orderby']="l.invest_time DESC";
			$list = getLoanRecordList($parm);
			$str_duration = $loan['duration_type'] == 0?"day":"month";

			$loan['interest_time'] =  strtotime("+{$loan['collect_day']}"."day",$loan['birth_time']);
			$loan['finish_time'] = strtotime("+{$loan['loan_duration']} ".$str_duration,$loan['interest_time']);
			$loan['birth_time'] = date('Y/m/d',$loan['birth_time']);
			$loan['interest_time']= date('Y/m/d',$loan['interest_time']);
			$loan['finish_time']  = date('Y/m/d',$loan['finish_time']);
			$loan['collect_time'] = date("Y/m/d H:i:s",$loan['collect_time']);
			$loan['duration_type']=duration_format($loan['duration_type']);
			$schedule_temp= M("loan_schedule") ->find($id);
			$schedule['cancel_warrants'] = (!$schedule_temp||$schedule_temp['cancel_warrants']==0)?"":date('Y/m/d',$schedule_temp['cancel_warrants']);
			$schedule['fund_trust']= (!$schedule_temp||$schedule_temp['fund_trust']==0)?"":date('Y/m/d',$schedule_temp['fund_trust']);
			$schedule['reowner']  =(!$schedule_temp||$schedule_temp['reowner']==0)?"":date('Y/m/d',$schedule_temp['reowner']);
	
			$loan_config=require C("APP_ROOT")."Conf/loan_config.php";
			$img_types=$loan_config['img_type'];
			$images[]=M('loan_img')->where("lid={$id} and type=0")->select();
			$images[]=M('loan_img')->where("lid={$id} and type=1")->select();
			$images[]=M('loan_img')->where("lid={$id} and type=2")->select();
			$config = require C("APP_ROOT")."Conf/config.php";
			$loan['repay_type']=$config['REPAY_TYPE'][$loan['repay_type']];
			$loan['loan_amount']=number_format($loan['loan_amount']);
			$detail['list']=$list;
			$detail['images']=$images;
			$detail['img_types']=$img_types;
			$detail['vo']=$loan;
			$detail['minfo']=$memberinfo;
			$detail['schedule']=$schedule;
			ajaxmsg($detail,0);
			
        }
        
        /**
        * 手机普通标投资
        */
        public function Invest(){   
        	
        	$uid=$_POST['uid'];
        	$id=$_POST['id'];
    		if (!$uid)   ajaxmsg("请先登录",1);
			if (!intval($id))   ajaxmsg("数据出错",1);
			$loan = M("loan")
				->where("id='{$id}'")
				->find();
			if ($uid == $loan['loan_uid']) ajaxmsg("不能对自己的项目进行投资",1);
			
			
			$data['id']=$id;
			$data['uid']=$uid;
			$data['need'] = number_format($loan['loan_amount'] - $loan['has_collect']);
			$data['account_money']=number_format(M('member_money')->getFieldByUid($uid,"account_money"),2);
			ajaxmsg($data,0);
			  
        }
		public function doinvest(){
			$uid =intval($_POST['uid']);
			$id = intval($_POST['id']);
			$paypass = md5($_POST['paypass']);
			$money=intval($_POST['invest_money']);
			if(!$id||!$uid||!$paypass||$money<=0) ajaxmsg("数据提交出错,请重试!",1);
			//募集期已满或者不在招标中
			$loan= M('loan')->find($id);
			if(($loan['status'] != 2)||($loan['collect_time']<time())) 
				ajaxmsg("该项目不在招标中",1);
			//判断支付密码
			if (M('members')->getFieldById($uid,"pin_pass")!= $paypass) 
				ajaxmsg("支付密码错误",1);
			//最小投标限制
			if(($loan['min_invest']?$min_invest:1)>$money) 
				ajaxmsg("不能低于最小投资额",1);
			//金额溢出标额
			$loan['has_collect'] = $loan['has_collect'] + $money;
			if ($loan['has_collect'] > $loan['loan_amount']) 
				ajaxmsg("金额超出需求,请重新投标！",1);
			//余额不足
			if (M("member_money")->getFieldByUid($uid,"account_money")<$money) 
				ajaxmsg(" 您的余额小于投资金额，请尽快充值",1);

			$freeze = memberMoneyLog($uid,6,-$money,"对{$loan_id}号{$loan['loan_name']}进行投标，冻结金额{$money}元！");
			if(!$freeze) ajaxmsg("投标失败",1);
			//保存loan
			if ($loan['has_collect'] == $loan['loan_amount']) $loan['status'] = 4;
			$newid = M('loan')->save($loan);
			if (!$newid) ajaxmsg("标的信息更新出错",1);
			//保存投资表
			$data['loan_id'] = $id;
			$data['invest_uid'] = $uid;
			$data['invest_amount']=$money;
			$data['invest_time'] = time();			
			$newid1 = M('loan_invest')->add($data);
			if ($newid1) {
				ajaxmsg("投资成功",0);
			}else {
				ajaxmsg("投标纪录保存失败",1);
			}
		}
    }
?>
