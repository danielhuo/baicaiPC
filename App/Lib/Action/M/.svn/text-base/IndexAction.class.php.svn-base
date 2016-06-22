<?php
    /**
    * 手机版(wap)默认首页
    * @author  张继立  
    * @time 2014-02-24
    */
    class IndexAction extends MobileAction
    {	
		var $notneedlogin=true;
        public function index()
        {
			if($_GET['invite']){
				
				session("invite",$_GET['invite']);
			}
            
            $this->display(); 
            
        }
		public function download()
        {
			
            
            $this->display(); 
            
        }
        
    }
?>
