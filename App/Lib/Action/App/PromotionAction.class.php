<?php
// 本类由系统自动生成，仅供测试用途
class PromotionAction extends AppAction {
	 var $needlogin=true;
    public function friends(){
		$promote=promote_data($this->uid);
		if(!$promote['total']['user'])
		ajaxmsg('您暂无推广数据',1);
	else
		ajaxmsg(promote_data($this->uid),0);
    }

}