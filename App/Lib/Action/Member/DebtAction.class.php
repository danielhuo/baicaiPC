<?php
    /**
    *  债权转让
    */
    class DebtAction extends MCommonAction
    {
        public $Detb;

        public function __construct()
        {
            parent::__construct();
            D("DebtBehavior");
            $this->Debt  = new DebtBehavior($this->uid);
        }
        /**
        * 债权转让默认页
        * 
        */
        public function index()
        {
           $this->display();
        } 
        /**
        * 可流转的标
        * 
        */
        public function change()
        {
           $list = $this->Debt->canTransfer();
           $this->assign('list', $list);
           $data['html'] = $this->fetch();
           exit(json_encode($data));
        }
        public function sellhtml()
        {
            $invest_id = isset($_GET['id'])? intval($_GET['id']):0;
            !$invest_id && ajaxmsg(L('parameter_error'),0);
            $info = $this->Debt->countDebt($invest_id);
            $this->assign('info', $info);
            $datag = get_global_setting();
            $this->assign('debt_fee', $datag['debt_fee']);
            $this->assign('invest_id', $invest_id);
            
            $borrow = M('borrow_investor i')
            ->join(C('DB_PREFIX')."borrow_info b ON i.borrow_id = b.id")
            ->field("borrow_name")
            ->where("i.id=".$invest_id)
            ->find();
            $this->assign("borrow_name", $borrow['borrow_name']);
            
            $d['content'] = $this->fetch();
            echo json_encode($d);
        }
        public function sell()
        {
            $money = floatval($_POST['money']);
            $paypass = $_POST['paypass'];
            $invest_id = intval($_POST['invest_id']);
            if($money && $paypass && $invest_id){
                $result = $this->Debt->sell($invest_id, $money, $paypass);
                if($result ==='TRUE')
                {
                    ajaxmsg('债权转让购买成功');   
                }else{
                    ajaxmsg($result,0);
                }
            }else{
                ajaxmsg('债权转让购买失败',0);
            }
            
            
        }
        /**
        * 进行中的债权
        * 
        */
        public function onBonds()
        {
            $list = $this->Debt->onBonds();
            $this->assign('list', $list);
            $data['html'] = $this->fetch();
            exit(json_encode($data));
        }
        /**
        *    成功的债权
        * 
        */
        public function successClaims()
        {
            $list = $this->Debt->successDebt();
            $this->assign('list', $list);
            $data['html'] = $this->fetch();
            exit(json_encode($data));
        }
        /**
        * 已购买的债权
        * 
        */
        public function buydetb()
        {
            $list = $this->Debt->buydetb();
            $this->assign('list', $list);
            $data['html'] = $this->fetch();
            exit(json_encode($data)); 
        }
        /**
        * 回收中的债权
        * 
        */
        public function ondetb()
        {
            $list = $this->Debt->onDetb();
            $this->assign('list', $list);
            $data['html'] = $this->fetch();
            exit(json_encode($data));
        }
        
        /**
        * 撤销转让债权ajax
        * 
        */
        public function cancelhtml()
        {
            $invest_id = $_REQUEST['invest_id'];
            $this->assign('invest_id', $invest_id);
            
            $d['content'] = $this->fetch();
            echo json_encode($d);
        }
        /**
        *  撤销债权转让
        * 
        */
        public function cancel()
        {
            $invest_id = $_REQUEST['invest_id'];
            $paypsss = strval($_POST['paypass']);
            !$invest_id && ajaxmsg(L('parameter_error'), 0);
        
            if($this->Debt->cancel($invest_id, $paypsss)) {
                ajaxmsg(L('撤销成功'), 1);
            }else{  
                ajaxmsg(L('撤销失败'), 0);
            }
            
        }
        
        /**
        * 取消的债权软让
        * 
        */
        public function cancellist()
        {
            $list = $this->Debt->cancelList();
            $this->assign('list', $list);
            $data['html'] = $this->fetch();
            exit(json_encode($data));
        }
        
        public function  agreement()
        {   
			
			
            $ht=M('hetong')->field('hetong_img,name,dizhi,tel')->find(); 
            $content = M("article_category")->field("type_content, type_name")->where("type_nid='agreement_debt'")->find();
            $this->assign('content', $content['type_content']);
            $this->assign('title', $content['type_name']);
            $this->assign('ht', $ht);
            
			
            $id = $this->_get('id','trim',0);
			$invest_detb=M("invest_detb")->field('invest_id,buy_time,money,transfer_price,sell_uid,buy_uid')->where("id=".$id)->find();
			$invest_id=$invest_detb['invest_id'];
			$sell_uid=$invest_detb['sell_uid'];
			$buy_uid=$invest_detb['buy_uid'];
			
			$seller=M("Members m")         
                    ->join(C('DB_PREFIX')."member_info mi ON m.id=mi.uid")	
                    ->field("m.user_name,mi.real_name,mi.idcard")
                    ->where("m.id=".$sell_uid)->find();
			$buyer=M("Members m")         
                    ->join(C('DB_PREFIX')."member_info mi ON m.id=mi.uid")	
                    ->field("m.user_name,mi.real_name,mi.idcard")
                    ->where("m.id=".$buy_uid)->find();
            $iBorrow=M("Borrow_investor bi")          	
                    ->field("bi.deadline,bi.birth_time,bi.investor_capital,borrow_uid")
                    ->where("bi.id=".$invest_id)->find();
			$borrow_uid=$iBorrow['borrow_uid'];
			$borrower=M("member_info mi")          	
                    ->field("mi.real_name")
                    ->where("mi.uid=".$borrow_uid)->find();
            $debt_total = $this->Debt->getAlsoPeriods($invest_id);
			$card_b=$buyer['idcard'];
			$card_b=substr($card_b,0,6).'********'.substr($card_b,14,4);
			$this->assign('card_b', $card_b);
			$card_s=$seller['idcard'];
			$card_s=substr($card_s,0,6).'********'.substr($card_s,14,4);
			$this->assign('card_s', $card_s);
			
		    $name_s=$seller['real_name'];
			$name_s=mb_substr($name_s, 0, 1,'utf-8').'**';
			$this->assign('name_s', $name_s);
			
			$name_b=$buyer['real_name'];
			$name_b=mb_substr($name_b, 0, 1,'utf-8').'**';
			$this->assign('name_b', $name_b);
			$name_a=$borrower['real_name'];
			$name_a=mb_substr($name_a, 0, 1,'utf-8').'**';
			$this->assign('name_a', $name_a);
		
			$this->assign('seller', $seller);
			$this->assign('invest_detb', $invest_detb);
			$this->assign('buyer', $buyer);
            $this->assign('debt_total', $debt_total);
           $this->assign('iBorrow', $iBorrow);
            $this->assign('invest_id', $invest_id);
			$this->assign('borrower', $borrower);
            $this->assign('debt', $debt);
            $this->display();
			

        }
      
    }
?>
