<?php
class ListAction extends BCommonAction {
    public function index(){
        /*$tlist = M("article")->field("title,art_content,art_time,type_id")->select();
        foreach ($tlist as $k => $v) {
            $v['art_writer'] = 'admin';
            switch ($v['type_id']) {
                case '9':
                    $v['menu'] = 1;
                    break;
                case '322':
                    $v['menu'] = 3;
                    $v['cat'] = 7;
                    $v['sort_order'] = 2;
                    break;
                case '2':
                    $v['menu'] = 2;
                    break;
                case '397':
                    $v['menu'] = 5;
                    break;
                case '344':
                    $v['menu'] = 3;
                    $v['cat'] = 7;
                    break;
                case '353':
                    $v['menu'] = 3;
                    $v['cat'] = 8;
                    break;
                case '354':
                    $v['menu'] = 3;
                    $v['cat'] = 9;
                    break;
            }
            unset($v['type_id']);
            M("forum_article")->add($v);
        }
        exit();*/

        $type_name = "使用板块";
        $map['a.is_hidden'] = 0;
    	if(intval($_GET['menu'])>0){
    		$map['a.menu'] = intval($_GET['menu']);

            $type_name = M('forum_article_category')->getFieldbyId($map['a.menu'],"type_name");

            $sonlist = M('forum_article_category')->field("type_name,id")->where("parent_id={$map['a.menu']}")->select();
            $this->assign("type_son",$sonlist);
    	}

        if(intval($_GET['cat'])>0){
            $map['a.cat'] = intval($_GET['cat']);
            $type_name = M('forum_article_category')->getFieldbyId($map['a.cat'],"type_name");
            $this->assign("type_son",null);
        }
        $this->assign("type_name", $type_name);

    	$sort = "a.sort_order DESC";
        if(isset($_GET['order']))   $sort .= ",".text($_GET['order'])." DESC";
        else $sort .= ",a.art_time DESC";
        
        if(isset($map['a.menu']) && in_array($map['a.menu'], array(10,11,12,13))){
            $sort .= ",a.last_repay_time DESC";//顶贴效果
        }

        if(text($_GET['searchType'])=="title"){
            $map['a.title'] = array("like","%".text($_GET['q'])."%");
        }elseif (text($_GET['searchType'])=="user") {
            if(text($_GET['q'])=="官方")  $map['a.art_uid'] = 1;
            else        $map['m.user_name'] = text($_GET['q']);
        }

    	//分页处理
		import("ORG.Util.Page");
		$count = M("forum_article a")->join("{$this->pre}members m ON m.id=a.art_uid")->where($map)->count("a.id");
		$p = new Page($count, 10);
		$page = $p->show();
		$Lsql = "{$p->firstRow},{$p->listRows}";
		//分页处理

		$list = M("forum_article a")->field("a.*,m.user_name,m.user_leve,m.time_limit")->join("{$this->pre}members m ON m.id=a.art_uid")->where($map)->order($sort)->limit($Lsql)->select();
        foreach ($list as $k => $v) {
            if($v['art_uid']==1)    $list[$k]['user_name'] = '官方';
            if($v['user_leve']==1 && $v['time_limit']>time())   $list[$k]['is_vip'] = true;

            $list[$k]['type_name'] = M('forum_article_category')->getFieldbyId( ($v['cat']==0)?$v['menu']:$v['cat'] ,"type_name");
        }
		$this->assign("list",$list);
		$this->assign("page", $page);
        $this->assign("type_num", $count);
        $this->display();
    }
}