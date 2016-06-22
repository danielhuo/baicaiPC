<?php
class IndexAction extends BCommonAction {
    public function index(){
    	$fnum = M('forum_article a')->field("c.type_name,a.menu,count(a.id) As count")->join("{$this->pre}forum_article_category c ON c.id=a.menu")->where("a.is_hidden=0")->group("a.menu")->select();
    	foreach ($fnum as $k => $v) {
    		$num[$v['menu']] = $v['count'];
    	}
        $this->assign('num', $num);

		$list = M('forum_article_category')->field("id,type_name,type_keyword")->where("parent_id=0 AND is_sys=1 AND is_show=1 ")->order("sort_order DESC,id ASC")->select();
		$list2 = M('forum_article_category')->field("id,type_name,type_keyword")->where("parent_id=0 AND is_sys=0 AND is_show=1 ")->order("sort_order DESC,id ASC")->select();
        $this->assign('list', $list);
        $this->assign('list2', $list2);

		$this->display();
    }
}