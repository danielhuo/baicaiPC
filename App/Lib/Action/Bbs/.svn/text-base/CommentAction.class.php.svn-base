<?php
class CommentAction extends BCommonAction {
    public function index(){
    	if(intval($_GET['aid'])>0){
    		$map['a.id'] = intval($_GET['aid']);
    	}else{
    		$this->error("传递参数错误，没有该篇帖子");
    	}

    	$data = M("forum_article a")->field("a.*,m.user_name,m.user_leve,m.time_limit")->join("{$this->pre}members m ON m.id=a.art_uid")->where($map)->find();
        $data['owner'] = ($this->uid>0 && $data['art_uid']==$this->uid) ? true :false;

    	if(!is_array($data)){
            $this->error("传递参数错误，没有该篇帖子");
        }elseif($data['is_hidden']==1){
            $this->assign('jumpUrl', "/bbs/list");
            $this->error("该篇帖子已被冻结，如有需要请联系管理员");
        }else{
            if($data['art_uid']==1)    $data['user_name'] = '官方';
            if($data['user_leve']==1 && $data['time_limit']>time())   $data['is_vip'] = true;
			
			if($data['cat']==0){
                $category = M('forum_article_category')->find($data['menu']);
            }else{
                $category = M('forum_article_category')->find($data['cat']);
            }
            $data['type_name'] = $category["type_name"];
            $data['is_repay'] = $category["is_repay"];

            $data['click_num']++;
            M("forum_article")->where("id={$data['id']}")->setInc("click_num",1);

            $pre = M('forum_article')->where("id<{$data['id']} AND menu={$data['menu']} AND cat={$data['cat']}")->order("id DESC")->find();
            $data['pre_id'] = $pre['id'];

            $next = M('forum_article')->where("id>{$data['id']} AND menu={$data['menu']} AND cat={$data['cat']}")->order("id ASC")->find();
            $data['next_id'] = $next['id'];
            $this->assign("data",$data);

            if($data['is_repay']==0){
                $this->display();
                exit();
            }

            //帖子回复
            //分页处理
            import("ORG.Util.Page");
            $count = M("forum_article_repay")->where("tid={$map['a.id']}")->count("id");
            $p = new Page($count, 10);
            $page = $p->show();
            $Lsql = "{$p->firstRow},{$p->listRows}";
            //分页处理

            $repayArr = M("forum_article_repay r")->field("r.*,m.user_name,m.user_leve,m.time_limit")->join("{$this->pre}members m ON m.id=r.art_uid")->where("tid={$map['a.id']}")->order("id ASC")->limit($Lsql)->select();
            foreach ($repayArr as $k => $v) {
                if($v['art_uid']==1)    $repayArr[$k]['user_name'] = '官方';
                if($v['user_leve']==1 && $v['time_limit']>time())   $repayArr[$k]['is_vip'] = true;

                if($v['art_time']>=strtotime("today"))  $repayArr[$k]['art_date'] = "今天 ".date("H:i",$v['art_time']);
                elseif($v['art_time']>=strtotime("today")-3600*24)  $repayArr[$k]['art_date'] = "昨天 ".date("H:i",$v['art_time']);
                else        $repayArr[$k]['art_date'] = date("Y-m-d H:i",$v['art_time']);
            }
            $this->assign("pos",$p->firstRow);
            $this->assign("repayArr",$repayArr);

			$this->display();
    	}
    }

	//回帖
    public function repay(){
        if(!$this->uid){
			$this->error("您必须登录后才可以回复");
		}
		
        $num = M("forum_article_repay")->where("art_uid={$this->uid} AND art_time>".strtotime("today"))->count("id");
        if($num>=100){
			$this->error("尊敬的客户，您每天的只可以发100条回复，请明天重试");
		}
		
        if(intval($_GET['aid'])>0){
            $map['a.id'] = intval($_GET['aid']);
        }else{
            $this->error("传递参数错误，没有该篇帖子");
        }

        if(isset($_POST['text'])){
            $data = M("forum_article a")->where($map)->find();
            if(!is_array($data)){
                $this->error("传递参数错误，没有该篇帖子");
            }elseif($data['is_repay']==1){
                $this->error("该篇帖子不可以回复");
            }else{
                $data['repay_num']++;
                $data['last_repay_time'] = time();
                M("forum_article")->save($data);
            }

            $repay['tid'] = $map['a.id'];
            $repay['content'] = text(str_replace("</emt>", "]]", str_replace("<emt>", "[[", trim($_POST['text'])) ));
            $repay['content'] = str_replace("[[", "<img src=\"/Style/B/images/face/" , str_replace("]]", ".gif\">", $repay['content'] ) );
            $repay['art_uid'] = $this->uid;
            $repay['art_time'] = time();

            if(strlen($repay['content'])==0){
				$this->error("评论里面什么东西都没有.");
			}
			
            M("forum_article_repay")->add($repay);
        }
        $this->assign('jumpUrl', "/bbs/comment?aid={$map['a.id']}&p=999999");
        $this->success('回复成功');
    }

    public function hidden_repay(){
        if(!$this->uid){
			$this->error("您必须登录后才可以屏蔽回复");
		}
		
        $id = intval($_POST['id']);
        $repay = M("forum_article_repay")->find($id);
        if(!is_array($repay))     $this->error("帖子回复参数错误，请刷新后重试");
        elseif($repay['art_uid']!=$this->uid)   $this->error("只有您自己帖子的回复才可以屏蔽");

        $repay['is_hidden'] = 1-$repay['is_hidden'];
        $re = M("forum_article_repay")->save($repay);

        if($re){
			$this->success("屏蔽成功");
        }else{
		    $this->error("屏蔽失败");
		}
    }
}