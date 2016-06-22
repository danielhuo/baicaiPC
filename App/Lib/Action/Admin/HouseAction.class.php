<?php
class HouseAction extends ACommonAction{
	public function index(){
		$houses=M('house h')
			->join("{$this->pre}members m on h.uid=m.id")
			->field('h.*,m.user_name')
			->select();
		foreach ($houses as $k=> $v) {
			$houses[$k]['xian']=M('Area')->getFieldById($v['xian'],'name');
			$houses[$k]['zhen']=M('Area')->getFieldById($v['zhen'],'name');
			$houses[$k]['village']=M('Area')->getFieldById($v['village'],'name');
		}
		$this->assign('houses',$houses);
		$this->display();
	}

	public function _editFilter(){
		$id=$_REQUEST['id'];
		$house=M('house')->find($id);
		$map['table']="house";
		$map['tid']=$id;
		$house['imgs']=M('images')->where($map)->select();
		$this->assign('house',$house);
	}
	public function doedit(){
		$data=$_POST;
		switch ($data['status']) {
			case '1':
			case '2':
				$data['verify_time']=time();
				break;
			case '3':
				$data['close_time']=time();
				break;
			default:
				break;
		}
		if (M('house')->save($data)) {
			$this->assign('jumpUrl', __URL__."/".session('listaction'));
			$this->success("更新成功");
		}else{
			$this->error("更新失败");
		}
	}
	
}
?>