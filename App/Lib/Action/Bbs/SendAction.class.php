<?php
class SendAction extends BCommonAction {
    public function index(){
		$list = M('forum_article_category')->field("id,type_name,type_keyword")->where("parent_id=0 AND is_sys=0")->order("sort_order DESC,id ASC")->select();
        $this->assign('list', $list);

		$this->display();
    }

    public function del(){
        if(!$this->uid){
			$this->error("您必须登录后才可以删除帖子");
		}
		
        $id = intval($_GET['aid']);
        $article = M("forum_article")->find($id);
        if(!is_array($article))     $this->error("传递参数错误，没有该篇帖子");
        elseif($article['art_uid']!=$this->uid)   $this->error("只有您自己的帖子才可以修改");

        $article['is_hidden'] = 1;
        $do = M("forum_article")->save($article);
        if($do){
            $this->assign('jumpUrl', "/bbs/list");
            $this->success('删除帖子成功');
        }else{
            $this->error("删除帖子失败");
        }
    }

    public function edit(){
        if(!$this->uid){
			$this->error("您必须登录后才可以修改帖子");
		}
        $id = intval($_GET['aid']);
        $article = M("forum_article")->find($id);
        if(!is_array($article))     $this->error("传递参数错误，没有该篇帖子");
        elseif($article['art_uid']!=$this->uid)   $this->error("只有您自己的帖子才可以修改");
        $this->assign('article', $article);

        $list = M('forum_article_category')->field("id,type_name,type_keyword")->where("parent_id=0 AND is_sys=0")->order("sort_order DESC,id ASC")->select();
        $this->assign('list', $list);

        $this->display("index");
    }

    public function doedit(){
        if(!$this->uid){
			$this->error("您必须登录后才可以发帖");
		}
        if(intval($_POST['id'])>0){
            $data = M("forum_article")->find(intval($_POST['id']));
            if(!is_array($data))     $this->error("传递参数错误，没有该篇帖子");
            elseif($data['art_uid']!=$this->uid)   $this->error("只有您自己的帖子才可以修改");
        }else{
            $num = M("forum_article")->where("art_uid={$this->uid} AND art_time>".strtotime("today"))->count("id");
            if($num>=5)    $this->error("尊敬的客户，您每天的只可以发5个新帖，请明天重试");
        }

    	$data['menu'] = intval($_POST['type']);
        $category = M('forum_article_category')->field("is_sys")->where("id={$data['menu']}")->find();
        if(!is_array($category))    $this->error("发帖类别错误，请重试");
        elseif ($category['is_sys']==1)      $this->error("该主题是官方主题，用户不可以发帖");

    	$data['title'] = cnsubstr(text($_POST['title']) ,50);

    	$text = trim($_POST['content']);
    	$text = htmlspecialchars_decode($text);//html解码，&lt;成为 < 
	    $data['art_content'] = mb_substr(safe($text,'html') ,0 ,5000);

    	$data['art_uid'] = $this->uid;
    	$data['art_time'] = time();
    	$data['last_repay_time'] = time();

        if(intval($_POST['id'])>0){
			$new = M("forum_article")->save($data);
        }else{
		    $new = M("forum_article")->add($data);
    	}
        if($new && intval($_POST['id'])>0){
            $this->assign('jumpUrl', "/bbs/comment?aid=".intval($_POST['id']));
            $this->success('修改成功');
        }elseif($new){
            $this->assign('jumpUrl', "/bbs/comment?aid={$new}");
            $this->success('发布成功');
        }else{
        	$this->success('发布失败');
        }
    }
}