<?php
// 全局设置
class BCommonAction extends Action
{
	var $glo=NULL;
	//上传参数
	var $savePathNew=NULL;
	var $thumbMaxWidthNew=NULL;
	var $thumbMaxHeightNew=NULL;
	var $thumbNew=NULL;
	var $allowExtsNew=NULL;
	var $siteInfo = NULL;
	//获取公共数据
	function _initialize(){
        $this->pre = C('DB_PREFIX');
		$datag = get_global_setting();
		$this->glo = $datag;//供PHP里面使用
        $this->assign("glo",$datag);

        $this->assign("tuijian",M("forum_article")->order("sort_order DESC")->find());
		$this->assign("retie",M("forum_article")->order("click_num DESC")->limit(5)->select());

        $all_num['all'] = M("forum_article")->count("id");
        $all_num['today'] = M("forum_article")->where("art_time>=".strtotime("today"))->count("id");
        $all_num['all_user'] = M("members")->count("id");
        $usersArr = M("members")->field("user_name")->order("reg_time DESC")->limit(3)->select();
        $this->assign("all_num",$all_num);
        $this->assign("usersArr",$usersArr);


 		if(session("u_user_name")){
			$this->uid = session("u_id");
			$unread=M("inner_msg")->where("uid={$this->uid} AND status=0")->count('id');
			$this->assign('unread',$unread);
			$this->assign('UID',$this->uid);
		}else{
			$loginconfig = FS("Webconfig/loginconfig");
			$de_val = cookie_authcode(cookie('UKey'),'DECODE',$loginconfig['cookie']['key']);
			if(substr(md5($loginconfig['cookie']['key'].$de_val),14,10) == cookie('Ukey2')){
				$vo = M('members')->field("id,user_name")->find($de_val);
				if(is_array($vo)){
					foreach($vo as $key=>$v){
						session("u_{$key}",$v);
					}
					$this->uid = session("u_id");
					$this->assign('UID',$this->uid);
					$unread=M("inner_msg")->where("uid={$this->uid} AND status=0")->count('id');
					$this->assign('unread',$unread);
				}else{
					cookie("Ukey",NULL);
					cookie("Ukey2",NULL);
				}
			}
		}
		
        if (method_exists($this, '_MyInit')) {
            $this->_MyInit();
        }
	}
	
	//上传图片
	function CUpload(){
		if(!empty($_FILES)){
			return $this->_Upload();
		}
	}
	
	function _Upload(){
		import("ORG.Net.UploadFile");
        $upload = new UploadFile();
		
		$upload->thumb = true;
		$upload->thumbMaxWidth = "10,50,200";
		$upload->thumbMaxHeight = "10,50,200";
		$upload->maxSize  = C('ADMIN_MAX_UPLOAD') ;// 设置附件上传大小
		$upload->allowExts  = C('ADMIN_ALLOW_EXTS');// 设置附件上传类型
		$upload->savePath =  $this->savePathNew?$this->savePathNew:C('ADMIN_UPLOAD_DIR');// 设置附件上传目录
		if(!$upload->upload()) {// 上传错误提示错误信息
			$this->error($upload->getErrorMsg());
		}else{// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
		}
		
		return $info;
	}
	//上传图片END

    /*根据表单生成查询条件进行列表过滤*/
    protected function _search($name = '') {
        //生成查询条件
        if (empty($name)) {
            $name = $this->getActionName();
        }
        $model = M($name);
        $map = array();
        foreach ($model->getDbFields() as $key => $val) {
            if (substr($key, 0, 1) == '_')
                continue;
            if (isset($_REQUEST[$val]) && $_REQUEST[$val] != '') {
                $map[$val] = $_REQUEST[$val];
            }
        }
        return $map;
    }

    /*根据表单生成查询条件进行列表过滤*/
    protected function _list($model, $field ='*', $map = array(), $sortBy = '', $asc = false) {
        //排序字段 默认为主键名
        $order = !empty($sortBy) ? $sortBy : $model->getPk();
        //排序方式默认按照倒序排列
        //接受 sost参数 0 表示倒序 非0都 表示正序
        $sort = $asc ? 'asc' : 'desc';
        //取得满足条件的记录数
        $count = $model->where($map)->count('id');
        import("ORG.Util.Page");
        //创建分页对象
        $listRows = !empty($_REQUEST['listRows'])?$_REQUEST['listRows']:C('ADMIN_PAGE_SIZE');
        $p = new Page($count, $listRows);
        //分页查询数据
        $list = $model->field($field)->where($map)->order($order . ' ' . $sort)->limit($p->firstRow . ',' . $p->listRows)->select();
        //分页跳转的时候保证查询条件
        foreach ($map as $key => $val) {
            if (!is_array($val)) {
                $p->parameter .= "$key=" . urlencode($val) . "&";
            }
        }

        if (method_exists($this, '_listFilter')) {
            $list = $this->_listFilter($list);
        }

        //分页显示
        $page = $p->show();
        //列表排序显示
        $sortImg = $sort;                                   //排序图标
        $sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列';    //排序提示
        $sort = $sort == 'desc' ? 1 : 0;                     //排序方式
		
        //模板赋值显示
        $this->assign('list', $list);
        $this->assign("pagebar", $page);
        return;
    }

	//添加
    public function add() {
        $this->display();
    }

	//编辑
    function edit() {
        $model = M($this->getActionName());
        $id = intval($_REQUEST['id']);
 		
		if (method_exists($this, '_editFilter')) {
            $this->_editFilter($id);
        }

       $vo = $model->find($id);
        $this->assign('vo', $vo);
        $this->display();
    }
	
	//添加数据
    public function doAdd() {
        $model = D($this->getActionName());
        if (false === $model->create()) {
            $this->error($model->getError());
        }
		
		if (method_exists($this, '_doAddFilter')) {
            $model = $this->_doAddFilter($model);
        }
		
        //保存当前数据对象
        if ($result = $model->add()) { //保存成功
            //成功提示
            $this->assign('jumpUrl', __URL__);
            $this->success(L('新增成功'));
        } else {
            //失败提示
            $this->error(L('新增失败'));
        }
    }
	
	//添加数据
    public function doEdit() {
        $model = D($this->getActionName());
        if (false === $model->create()) {
            $this->error($model->getError());
        }
		
		if (method_exists($this, '_doEditFilter')) {
            $model = $this->_doEditFilter($model);
        }
		
        //保存当前数据对象
        if ($result = $model->save()) { //保存成功
            //成功提示
            $this->assign('jumpUrl', __URL__);
            $this->success(L('修改成功'));
        } else {
            //失败提示
            $this->error(L('修改失败'));
        }
    }

	//删除数据
	public function doDel(){
        $model = D($this->getActionName());
        if (!empty($model)) {
			$id = $_REQUEST['idarr'];
            if (isset($id)) {
				if (method_exists($this, '_doDelFilter')) {
					$this->_doDelFilter($id);
				}
			
                if (false !== $model->where("id in ({$id})")->delete()) {
                    $this->success(L('删除成功'),'',$id);
                } else {
                    $this->error(L('删除失败'));
                }
            } else {
                $this->error('非法操作');
            }
        }
	}
}
?>