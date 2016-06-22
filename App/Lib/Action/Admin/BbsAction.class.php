<?php
// 全局设置
class BbsAction extends ACommonAction
{
    public function index(){
    	$tlist = M("forum_article_category")->field("type_name,id,parent_id")->select();
    	foreach ($tlist as $k => $v) {
    		$tArr[$v['id']] = $v;
    	}
		$this->assign('tArr',$tArr);

    	if(isset($_GET['menu'])){
    		$map['menu']= intval($_GET['menu']);
    	}

    	$sort = "sort_order DESC,id DESC";
    	if(isset($map['menu']) && in_array($map['menu'], array(10,11,12,13))){
    		$sort = "sort_order DESC,last_repay_time DESC";//顶贴效果
    	}

    	//分页处理
		import("ORG.Util.Page");
		$count = M('forum_article')->where($map)->count("id");
		$p = new Page($count, 10);
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理

		$list = M('forum_article')->where($map)->order($sort)->limit($Lsql)->select();
        $this->assign('list', $list);
        $this->assign('pagebar', $page);
        $this->display();
    }

    public function add(){
		$typelist = M('forum_article_category')->field("id,type_name")->where("parent_id=0")->select();
		$arr = array();
		foreach ($typelist as $k => $v) {
			array_push($arr,$v);
			$child = M('forum_article_category')->field('id,type_name')->where(array("parent_id"=>$v['id']))->select();
			foreach ($child as $key => $val) {
				$val['type_name'] = "----".$val['type_name'];
				array_push($arr,$val);
			}
		}
		$this->assign('type_list',$arr);
		$this->display("edit");
    }

	public function edit(){
		$id = intval($_GET['id']);
		$vo = M('forum_article')->where("id=$id")->find();
		if(!is_array($vo))	$this->error("参数错误，请重试");

		$vo['type_id'] = ($vo['cat']!=0)?$vo['cat']:$vo['menu'];
		$this->assign('vo',$vo);

		$typelist = M('forum_article_category')->field("id,type_name")->where("parent_id=0")->select();
		$arr = array();
		foreach ($typelist as $k => $v) {
			array_push($arr,$v);
			$child = M('forum_article_category')->field('id,type_name')->where(array("parent_id"=>$v['id']))->select();
			foreach ($child as $key => $val) {
				$val['type_name'] = "----".$val['type_name'];
				array_push($arr,$val);
			}
		}
		$this->assign('type_list',$arr);

		$this->display();
	}
	public function _doEditFilter($m){
		if(!empty($_FILES['imgfile']['name'])){
			$this->saveRule = date("YmdHis",time()).rand(0,1000);
			$this->savePathNew = C('ADMIN_UPLOAD_DIR').'Bbs_tuijian/';
			$this->thumbMaxWidth = C('ARTICLE_UPLOAD_W');
			$this->thumbMaxHeight = C('ARTICLE_UPLOAD_H');
			$info = $this->CUpload();
			$data['art_img'] = $info[0]['savepath'].$info[0]['savename'];
		}
		if($data['art_img']) $m->art_img=$data['art_img'];
		if($_POST['is_remote']==1) $m->art_content = get_remote_img($m->art_content);
		return $m;
	}
	public function doEdit(){
		$data['title'] = text($_POST['title']);
		$data['sort_order'] = intval($_POST['sort_order']);
		$data['is_hidden'] = (intval($_POST['is_hidden'])==1)?1:0;
		$data['art_time'] = (strtotime(text($_POST['art_time']))!=0)?strtotime(text($_POST['art_time'])):time();
    	$data['last_repay_time'] = time();

		$text = trim($_POST['art_content']);
    	$text = htmlspecialchars_decode($text);//html解码，&lt;成为 < 
	    $data['art_content'] = safe($text,'html');

		$type = intval($_POST['type_id']);
		$child = M('forum_article_category')->field('parent_id')->where("id=$type")->find();

		if($child['parent_id']==0){
			$data['menu'] = $type;
			$data['cat'] = 0;
		}else{
			$data['menu'] = $child['parent_id'];
			$data['cat'] = $type;
		}

		if(!empty($_FILES['imgfile']['name'])){
			$this->savePathNew = C('ADMIN_UPLOAD_DIR').'Bbs_tuijian/';
			$this->thumbMaxWidth = C('ARTICLE_UPLOAD_W');//240;
			$this->thumbMaxHeight = C('ARTICLE_UPLOAD_H');//154;
			$info = $this->CUpload();
			$data['art_img'] = $info[0]['savepath'].$info[0]['savename'];
		}
		
		if(intval($_POST['id'])>0){
			$data['id'] = intval($_POST['id']);
			$new = M('forum_article')->save($data);
		}else{
			$new = M('forum_article')->add($data);
		}
    	
        if ($new) {
            $this->assign('jumpUrl', __URL__."/index");
            $this->success(L('修改成功'));
        } else {
            $this->error(L('修改失败'));
        }
	}

	public function del(){
		$id = intval($_GET['id']);
		$do = M('forum_article')->where("id=$id")->delete();
		if ($do) {
            $this->success(L('删除成功'));
        } else {
            $this->error(L('删除失败'));
        }
	}


	public function repay(){
    	if(isset($_GET['tid'])){
    		$map['r.tid']= intval($_GET['tid']);
    	}

    	//分页处理
		import("ORG.Util.Page");
		$count = M('forum_article_repay r')->where($map)->count("r.id");
		$p = new Page($count, 10);
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理

		$list = M('forum_article_repay r')->field("r.*,a.title,m.user_name")->join("{$this->pre}forum_article a ON a.id=r.tid")->join("{$this->pre}members m ON m.id=r.art_uid")->where($map)->limit($Lsql)->select();
        $this->assign('list', $list);
        $this->assign('pagebar', $page);
        $this->display();
    }

    /*public function addRepay(){
		$typelist = M('forum_article_category')->field("id,type_name")->where("parent_id=0")->select();
		$arr = array();
		foreach ($typelist as $k => $v) {
			array_push($arr,$v);
			$child = M('forum_article_category')->field('id,type_name')->where(array("parent_id"=>$v['id']))->select();
			foreach ($child as $key => $val) {
				$val['type_name'] = "----".$val['type_name'];
				array_push($arr,$val);
			}
		}
		$this->assign('type_list',$arr);
		$this->display("edit");
    }*/

	public function editRepay(){
		$id = intval($_GET['id']);
		$vo = M('forum_article_repay r')->field("r.*,m.user_name")->join("{$this->pre}members m ON m.id=r.art_uid")->where("r.id=$id")->find();
		if(!is_array($vo))	$this->error("参数错误，请重试");
		$this->assign('vo',$vo);
		$this->display();
	}

	public function doEditRepay(){
		$data['is_hidden'] = (intval($_POST['is_hidden'])==1)?1:0;
		$data['content'] = stripslashes(safe($_POST['content'],'html'));
		$data['art_time'] = strtotime(text($_POST['art_time']));

		if(intval($_POST['id'])>0){
			$data['id'] = intval($_POST['id']);
			$new = M('forum_article_repay')->save($data);
		}else{
			$new = M('forum_article_repay')->add($data);
		}
    	
        if ($new) {
            $this->assign('jumpUrl', __URL__."/repay");
            $this->success(L('修改成功'));
        } else {
            $this->error(L('修改失败'));
        }
	}

	public function delRepay(){
		$id = intval($_GET['id']);
		$do = M('forum_article_repay')->where("id=$id")->delete();
		if ($do) {
            $this->success(L('删除成功'));
        } else {
            $this->error(L('删除失败'));
        }
	}


    public function type(){
		$list = M('forum_article_category')->where(array("parent_id"=>0))->select();
		foreach($list as $key=>$val){
			$child = M('forum_article_category')->field('id')->where(array("parent_id"=>$val['id']))->find();
			if(is_array($child)){
				$list[$key]['haveson']=true;
			}else{
				$list[$key]['haveson']=false;
			}
		}

        $this->assign('list', $list);
        $this->display();
    }
	
    public function listType(){
		$typeid=intval($_REQUEST['typeid']);
		$sonlist = M('forum_article_category')->where("parent_id={$typeid}")->select();
		$list="";
		foreach($sonlist as $key=>$v){
			$is_show = ($v['is_show']==1) ? "显示" : "隐藏";
			$is_sys = ($v['is_sys']==1) ? "官方发布" : "用户发布";
			$is_repay = ($v['is_repay']==1) ? "可以回复" : "不可以回复";

			$list.='<tr overstyle="on" id="list_'.$v['id'].'" class="leve_2" typeid="'.$v['id'].'" parentid="'.$v['parent_id'].'">
		        <td><input type="checkbox" name="checkbox" id="checkbox2" onclick="checkon(this)" value="'.$v['id'].'"></td>
		        <td>'.$v['id'].'</td>
		        <td><span class="typeson">&nbsp;</span>'.$v['type_name'].'</td>
		        <td>'.$v['type_nid'].'&nbsp;</td>
		        <td>'.$v['type_keyword'].'&nbsp;</td>
		        <td>'.$is_show.'</td>
		        <td>'.$is_sys.'</td>
		        <td>'.$is_repay.'</td>
		        <td>
		            <a href="'.__URL__.'/editType?id='.$v['id'].'">编辑</a> 
		            <a href="'.__URL__.'/delType?id='.$v['id'].'">删除</a>  
		        </td>
		      </tr>';
		}

		$data['inner'] = $list;
		$data['typeid'] = $typeid;
		$this->ajaxReturn($data,"");
    }
	
    public function addType(){
		$typelist = M('forum_article_category')->field("id,type_name")->where("parent_id=0")->select();
		$this->assign('type_list',$typelist);
		$this->display("edittype");
    }

	public function editType(){
		$id = intval($_GET['id']);
		$vo = M('forum_article_category')->where("id=$id")->find();
		if(!is_array($vo))	$this->error("参数错误，请重试");

		$typelist = M('forum_article_category')->field("id,type_name")->where("parent_id=0 AND id<>$id")->select();
		$this->assign('type_list',$typelist);
		$this->assign('vo',$vo);
		$this->display();
	}

	public function doEditType(){
		$data['type_nid'] = text($_POST['type_nid']);
		$model = M('forum_article_category')->where($data)->find();

		$data['type_name'] = text($_POST['type_name']);
		$data['type_keyword'] = text($_POST['type_keyword']);
		$data['parent_id'] = intval($_POST['parent_id']);

		$data['is_show'] = intval($_POST['is_show'])==0 ? 0 : 1;
		$data['is_sys'] = intval($_POST['is_sys'])==0 ? 0 : 1;
		$data['is_repay'] = intval($_POST['is_repay'])==0 ? 0 : 1;
    
		if(intval($_POST['id'])>0){
			$data['id'] = intval($_POST['id']);
			if(is_array($model) && $model['id']!=$data['id']){
				$this->error(L('修改失败,唯一标志必须唯一'));
			}else{
				$new = M('forum_article_category')->save($data);
			}
		}else{
			if(is_array($model)){
				$this->error(L('修改失败,唯一标志必须唯一'));
			}else{
				$new = M('forum_article_category')->add($data);
			}
		}
    	
        if ($new) {
            $this->assign('jumpUrl', __URL__."/type");
            $this->success(L('修改成功'));
        } else {
            $this->error(L('修改失败'));
        }
	}

	public function delType(){
		$id = intval($_GET['id']);
		$vo = M('forum_article_category')->where("id=$id")->find();
		if(!is_array($vo))	$this->error("参数错误，请重试");

		if($vo['parent_id']==0){
			$do = M('forum_article_category')->where("parent_id=$id")->delete();
		}

		$do = M('forum_article_category')->where("id=$id")->delete();
		if ($do) {
            $this->success(L('删除成功'));
        } else {
            $this->error(L('删除失败'));
        }
	}
}
?>