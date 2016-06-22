<?php
// 全局设置
class ReservationAction extends ACommonAction
{
    /**
    +----------------------------------------------------------
    * 默认操作
    +----------------------------------------------------------
    */
    public function select(){
		import("ORG.Util.Page");
		$count = M('reservation_project')->count('id');
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理
		$list = M('reservation_project')->limit($Lsql)->select();
		//$list = $this->_listFilter($list);
        $this->assign("list", $list);
        $this->assign("pagebar", $page);
		$this->display();
    }
	public function add()
    {
		$this->display();
    }
	public function doadd(){
		$data['name'] = $_POST['name'];
		$data['amount'] = $_POST['money'];
		$data['duration'] = $_POST['time'];
		$data['interest_rate'] = $_POST['interest'];
		$data['min_invest'] = $_POST['min'];
		$data['detail'] = $_POST['detail'];
		if ($data['amount'] < 0 || $data['min_invest'] < 0 ){
			$this->error("金额输入错误，请重试");
		}
		if (empty($data['name']) || empty($data['duration']) || empty($data['interest_rate']) ||empty($data['detail'])){
			$this->error("信息发布错误，请重试");
		}
		$new = M('reservation_project')->add($data);
		if ($new) {
			$this->success("预约项目发布成功",__APP__."/admin/Reservation/select");
		}else{
			$this->error("发布失败，请先检查借款信息是否正确，然后重试");
		} 
		
		
    }
	public function doinvest(){
		$map['r_id'] = intval($_REQUEST['id']);
		//分页处理
		import("ORG.Util.Page");
		$count = M('reservation_invest')->where($map)->count('id');
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		
		$list = M('reservation_invest ri')->join("{$this->pre}members m ON m.id=ri.invest_uid")->join("{$this->pre}reservation_project r ON r.id=ri.r_id")->limit($Lsql)->where($map)->select();
		$this->assign("list", $list);
        $this->assign("pagebar", $page);
		$this->assign("r_id", $map['r_id']);
		$this->display();
	}
    public function addinvest(){
		$map['r_id'] = intval($_REQUEST['id']);
		$this->assign("r_id", $map['r_id']);
		$this->display();
	}
	 public function doaddinvest(){
		$r_id= intval($_POST['r_id']);
		$name= trim($_POST['name']);
		$invest_amount= $_POST['money'];
		$map['user_name']=$name;
		$user_name = M('members')->where($map)->find();
		$invest_uid= $user_name['id'];
		
		$data= M('reservation_project')->find($r_id);
		
		$data['has_borrow'] +=$invest_amount;
		
		$has = M('reservation_project')->save($data);
		
		if ($has==false) $this->error("募集信息更新失败，请重试");
		$data1['r_id']=$r_id;
		$data1['invest_uid']=$invest_uid;
		$data1['invest_amount']=$invest_amount;
		$new = M('reservation_invest')->add($data1);
		
		if ($new) {
			$this->success("投资信息更新成功！",__APP__."/admin/Reservation/doinvest?id=$r_id");
		}else{
			$this->error("投资信息更新失败，请重试");
		} 
	}
	public function doapply() {
		$map['p_id'] = intval($_REQUEST['id']);
		$map['type'] = 2 ;
		
		import("ORG.Util.Page");
		$count = M('accesstel')->where($map)->count('id');
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		
		$list = M('accesstel')->limit($Lsql)->where($map)->select();
		$this->assign("list", $list);
        $this->assign("pagebar", $page);
		$this->display();
	}
	public function setaccesstel(){
		$data['id'] = $_GET['id'];
		$data['status'] = 1;
		$new = M('accesstel')->save($data);
		M('accesstel')->find($data['id']);
		$this->success("修改成功");
		
	}
	public function accesstel(){
		import("ORG.Util.Page");
		$type=$_GET['type'];
		$map['type']=$type;
		$count = M('accesstel')->where($map)->count('id');
		$p = new Page($count, C('ADMIN_PAGE_SIZE'));
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理
		$list = M('accesstel a')->join("lzh_reservation_project r ON a.p_id = r.id")->field("a.*,r.name,r.id as rid")->order("a.status ASC,a.access_time DESC")->where($map)->limit($Lsql)->select();
		//$list = $this->_listFilter($list);
        $this->assign("list", $list); 
		$this->assign("type",$type);
        $this->assign("pagebar", $page);
		$this->display();
    }
}
?>