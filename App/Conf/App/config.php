<?php
return array(
    'ERROR_PAGE'    =>'/Pub/404.html',
   
    'TMPL_FILE_DEPR'=>'/',
    'TMPL_ACTION_ERROR' => 'Pub:error',
    'TMPL_ACTION_SUCCESS' => 'Pub:success',
    'App_MAX_UPLOAD'=>2000000,//后台上传文件最大限制2M
    'ADMIN_ALLOW_EXTS'=>array('jpg', 'gif', 'png', 'jpeg'),
	'App_UPLOAD_DIR'=>'UF/Uploads/',//后台上传目录
    //'DEFAULT_GROUP'     =>'M',//默认分组 
    'URL_ROUTER_ON'    =>false,//开启路由规则
    'URL_HTML_SUFFIX'=>".html",//静态文件后缀
    
);
?>