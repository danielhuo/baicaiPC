<?php
class HouseAction extends AppAction{
	public function index(){	
			$uid = $_GET['uid'];
			if(!empty($uid)) {
				$searchMap['uid']= $uid;
				$parm['map'] =$searchMap;
			}

            $parm['pagesize'] =6;
            $parm['orderby']="h.id DESC";
            $list = getHouseList($parm);
            if(($list['page']['nowPage']>$list['page']['total'])||!$list|!$list['list'])
            	ajaxmsg("加载失败",1);
            else ajaxmsg($list,0);	  
        }

	public function getselect(){
		$aid = $_POST['aid'];
		//$c =array("你好","hehe");
		$list['list'] = M('area')->where("reid={$aid}")->select();	
		ajaxmsg($list,0);
	}

	public function detail(){
	
           $id = intval($_GET['id']);
           $data['house'] =getHouseInfo($id);
           ajaxmsg($data,0);
	}

	public function publish(){
		$uid=$_POST['uid'];
		//ajaxmsg($_POST,1);
		(!$uid||!M('Members')->find($uid))&&ajaxmsg("用户不存在",1);
		$xian=$_POST['xian'];
		$zhen=$_POST['zhen'];
		$village=$_POST['village'];
		$size=$_POST['size'];
		$price=$_POST['price'];
		$direction=$_POST['direction'];
		$total_floor=$_POST['total_floor'];
		$current_floor=$_POST['current_floor'];
		$bed_room=$_POST['bed_room'];
		$live_room=$_POST['live_room'];
		$toilet=$_POST['toilet'];
		$house_age=$_POST['house_age'];
		$decoration=$_POST['decoration'];
		$title=$_POST['title'];
		$describe=$_POST['describe'];
		/*上传文件开始*/
		$this->saveRule = 'uniqid';
		$this->savePathNew = 'UF/Uploads/house/' ;
		$this->thumbMaxWidth = "200";
		$this->thumbMaxHeight = "200";
		$info = $this->CUpload();
		$info['status']==0&&ajaxmsg($info['info'],1);
		/*上传文件结束*/

		
		(!$xian||!$zhen||!$village||!$size||!$price||!$direction||!$total_floor||!$current_floor||!$bed_room||!$live_room||!$toilet||!$house_age||!$decoration||!$title||!$describe)&&ajaxmsg("提交的信息不完整",1);
		$data=$_POST;
		$data['birth_time'] = time();
		$data['status'] = 0;


		$id = M('House')->add($data);

		if($id){
			$img['table'] ='house';
			$img['tid'] = $id;
			$img_info =$info['info'];
			for($i=0;$i<count($img_info);$i++){
				$img['url'] = $img_info[$i]['savepath'].$img_info[$i]['savename'];
				$img['thumb_url'] = $img_info[$i]['savepath'].'thumb_'.$img_info[$i]['savename'];
				$mid = M('images')->add($img);
			}

			ajaxmsg("上传成功，等待后台审核",0);
		}
		else ajaxmsg("发布失败",1);
	}
}
?>