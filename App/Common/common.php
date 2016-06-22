<?php
require APP_PATH."Common/Lib.php";
require APP_PATH."Common/DataSource.php";
//require APP_PATH."Common/Refusedcc.php";//防御CC攻击  fan  2013-11-28
function acl_get_key(){
	empty($model)?$model=strtolower(MODULE_NAME):$model=strtolower($model);
	empty($action)?$action=strtolower(ACTION_NAME):$action=strtolower($action);
	
	$keys = array($model,'data','eqaction_'.$action);
	require C('APP_ROOT')."Common/acl.inc.php";
	$inc = $acl_inc;
	
	$array = array();
	foreach($inc as $key => $v){
			if(isset($v['low_leve'][$model])){
				$array = $v['low_leve'];
				continue;
			}
	}//找到acl.inc中对当前模块的定义的数组
	
	
	$num = count($keys);
	$num_last = $num - 1;
	$this_array_0 = &$array;
	$last_key = $keys[$num_last];
	
	for ($i = 0; $i < $num_last; $i++){
		$this_key = $keys[$i];
		$this_var_name = 'this_array_' . $i;
		$next_var_name = 'this_array_' . ($i + 1);        
		if (!array_key_exists($this_key, $$this_var_name)) {            
			break;       
		}        
		$$next_var_name = &${$this_var_name}[$this_key];    
	}    
	/*取得条件下的数组  ${$next_var_name}得到data数组 $last_key即$keys = array($model,'data','eqaction_'.$action);里面的'eqaction_'.$action,所以总的组成就是，在acl.inc数组里找到键为$model的数组里的键为data的数组里的键为'eqaction_'.$action的值;*/
	$actions = ${$next_var_name}[$last_key];

	//这个值即为当前action的别名,然后用别名与用户的权限比对,如果是带有参数的条件则$actions是数组，数组里有相关的参数限制
	if(is_array($actions)){
		foreach($actions as $key_s => $v_s){
			$ma = true;
			if(isset($v_s['POST'])){
				foreach($v_s['POST'] as $pkey => $pv){
					switch($pv){
						case 'G_EMPTY';//必须为空
							if( isset($_POST[$pkey]) && !empty($_POST[$pkey]) ) $ma = false;
						break;
					
						case 'G_NOTSET';//不能设置
							if( isset($_POST[$pkey]) ) $ma = false;
						break;
					
						case 'G_ISSET';//必须设置
							if( !isset($_POST[$pkey]) ) $ma = false;
						break;
					
						default;//默认
							if( !isset($_POST[$pkey]) || strtolower($_POST[$pkey]) != strtolower($pv) ) $ma = false;
						break;
					}
				}
			}
			
			if(isset($v_s['GET'])){
				foreach($v_s['GET'] as $pkey => $pv){
					switch($pv){
						case 'G_EMPTY';//必须为空
							if( isset($_GET[$pkey]) && !empty($_GET[$pkey]) ) $ma = false;
						break;
					
						case 'G_NOTSET';//不能设置
							if( isset($_GET[$pkey]) ) $ma = false;
						break;
					
						case 'G_ISSET';//必须设置
							if( !isset($_GET[$pkey]) ) $ma = false;
						break;
					
						default;//默认
							if( !isset($_GET[$pkey]) || strtolower($_GET[$pkey]) != strtolower($pv) ) $ma = false;
						break;
					}
					
				}
			}
			if($ma)	return $key_s;
			else $actions="0";
		}//foreach
	}else{
		return $actions;
	}
}
//////////////////////////////////// 第三方支付--移动支付专用 开始 fan 2014-06-07 ////////////////////////////
//* 移动支付使用该方法
//获取客户端ip地址
//注意:如果你想要把ip记录到服务器上,请在写库时先检查一下ip的数据是否安全.
//*
function getIp() {
        if (getenv('HTTP_CLIENT_IP')) {
				$ip = getenv('HTTP_CLIENT_IP'); 
		}
		elseif (getenv('HTTP_X_FORWARDED_FOR')) { //获取客户端用代理服务器访问时的真实ip 地址
				$ip = getenv('HTTP_X_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_X_FORWARDED')) { 
				$ip = getenv('HTTP_X_FORWARDED');
		}
		elseif (getenv('HTTP_FORWARDED_FOR')) {
				$ip = getenv('HTTP_FORWARDED_FOR'); 
		}
		elseif (getenv('HTTP_FORWARDED')) {
				$ip = getenv('HTTP_FORWARDED');
		}
		else if(!empty($_SERVER["REMOTE_ADDR"])){
				$cip = $_SERVER["REMOTE_ADDR"];  
		}else{
				$cip = "unknown";  
		}
		return $ip;
}

	//移动支付MD5方式签名
	  function MD5sign($okey,$odata){
	  		$signdata=hmac("",$odata);			     
	  		return hmac($okey,$signdata);
	  }
	  
	  /*function hmac ($key, $data){
		  $key = iconv('gb2312', 'utf-8', $key);
		  $data = iconv('gb2312', 'utf-8', $data);
		  $b = 64;
		  if (strlen($key) > $b) {
		  		$key = pack("H*",md5($key));
		  }
		  $key = str_pad($key, $b, chr(0x00));
		  $ipad = str_pad('', $b, chr(0x36));
		  $opad = str_pad('', $b, chr(0x5c));
		  $k_ipad = $key ^ $ipad ;
		  $k_opad = $key ^ $opad;
		  return md5($k_opad . pack("H*",md5($k_ipad . $data)));
      }*/ 
	  
	  function HmacMd6($data, $key) {
    // RFC 2104 HMAC implementation for php.
    // Creates an md5 HMAC.
    // Eliminates the need to install mhash to compute a HMAC
    // Hacked by Lance Rushing(NOTE: Hacked means written)

    //需要配置环境支持iconv，否则中文参数不能正常处理
   //$key = iconv("GB2312", "UTF-8", $key);
    ///$data = iconv("GB2312", "UTF-8", $data);

    $b = 64; // byte length for md5
    if (strlen($key) > $b) {
        $key = pack("H*", md5($key));
    }
    $key = str_pad($key, $b, chr(0x00));
    $ipad = str_pad('', $b, chr(0x36));
    $opad = str_pad('', $b, chr(0x5c));
    $k_ipad = $key ^ $ipad;
    $k_opad = $key ^ $opad;

    return md5($k_opad . pack("H*", md5($k_ipad . $data)));
}
//////////////////////////////////// 第三方支付--移动支付专用 结束 fan 2014-06-07 ////////////////////////////	 

//////////////////////////////////cookie加密算法 //////////////////////////////////////////////
	function cookie_authcode($string, $operation = 'DECODE', $key = '') {
		// 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
		$ckey_length = 4;
		// 密匙
		$key = md5($key ? $key : "lzh_jiedai");
		// 密匙a会参与加解密
		$keya = md5(substr($key, 0, 16));
		// 密匙b会用来做数据完整性验证
		$keyb = md5(substr($key, 16, 16));
		// 密匙c用于变化生成的密文
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
		// 参与运算的密匙
		$cryptkey = $keya.md5($keya.$keyc);
		$key_length = strlen($cryptkey);
		// 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
		// 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', 0).substr(md5($string.$keyb), 0, 16).$string;
		$string_length = strlen($string);
		$result = '';
		$box = range(0, 255);
		$rndkey = array();
		
		// 产生密匙簿
		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}
		
		// 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}
		
		// 核心加解密部分
		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			// 从密匙簿得出密匙进行异或，再转成字符
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}
		
		if($operation == 'DECODE') {
			// substr($result, 0, 10) == 0 验证数据有效性
			// substr($result, 0, 10) - time() > 0 验证数据有效性
			// substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性
			// 验证数据有效性，请看未加密明文的格式
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
				return substr($result, 26);
			} else {
				return '';
			}
		} else {
			// 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
			// 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
			return $keyc.str_replace('=', '', base64_encode($result));
		}
	} 
	////////////////////////////////////////////
	function promote_data($uid){
		$map['m.recommend_id']=$uid;	
		$promote=array(
			'total' => array( "user" => 0, 'invest'=>0,'award'=>0 ),
			'detail' => null
		);

		$total['user'] = M('members m')
		->where($map)
		->count('id');	
		if(!$total['user']) return $promote;
	
		$total['invest'] = M('members m')
		->join('lzh_loan_invest li on li.invest_uid=m.id')
		->where($map)
		->sum('li.invest_amount');

		if($total['invest']){
		$total['award']=M('member_moneylog')
		->where("type=13 and uid={$uid}")
		->sum('affect_money');
		$total['award'] = $total['award']==null?0:number_format($total['award'],2);
		}
			
		$promote['total'] =$total;
		$detail = M('members m')->join('lzh_loan_invest li on li.invest_uid=m.id')
		->field('m.user_name,m.reg_time,sum(li.invest_amount) as invest_total')
		->where($map)
		->group('m.id')		
		->select();
		foreach($detail as $key => $value){
			$detail[$key]['invest_total'] = $value['invest_total'] == NULL?0:number_format($value['invest_total'],2);
			$detail[$key]['reg_time'] = date("Y/m/d",$value['reg_time']);
		}	

		$promote['detail'] = $detail;
		return $promote;
	}

	function getHouseList($parm=array()){
	    $map= $parm['map'];
	    $orderby= $parm['orderby'];
	    
	    if($parm['pagesize']){
	        //分页处理
	        import("ORG.Util.Page");
	        $count = M('house h')->count('h.id');
	        $p = new Page($count, $parm['pagesize']);
	        $page = $p->show();
	        $Lsql = "{$p->firstRow},{$p->listRows}";

	        $row['page']['total'] = ceil($count/$parm['pagesize']);
	        $row['page']['nowPage'] =  isset($_REQUEST['p'])?$_REQUEST['p']:1; 
	        //分页处理
	    }else{
	        $page="";
	        $Lsql="{$parm['limit']}";
	    }
	    $pre = C('DB_PREFIX');
	    $suffix=C("URL_HTML_SUFFIX");
	  
	    $list = M('house h')->where($map)->order($orderby)->limit($Lsql)->select();

	  //  $pic_url='http://localhost/UF/Uploads/House/1.jpg';
		foreach($list as $key=>$v){
	        $pic_url = M('images')->field("thumb_url")->where("tid={$v['id']}")->find();
	        $pic_url =$pic_url['thumb_url'];
	        $status_str="";
	        switch ($v['status']) {
	        	case '0':
	        		$status_str="待审核";
	        		break;
	        	case '1':
	        		$status_str="出售中";
	        		break;
        		case 'value':
        			$status_str="审核未通过";
        		break;
        		case 'value':
	        		$status_str="已关闭";
	        		break;
	        	
	        	default:
	        		# code...
	        		break;
	        }
	        $list[$key]['status_str'] =$status_str;
			$list[$key]['xian_name'] = M('Area')->getFieldById($v['xian'],'name');
			$list[$key]['zhen_name'] = M('Area')->getFieldById($v['zhen'],'name');
			$list[$key]['village_name'] = M('Area')->getFieldById($v['village'],'name');
	        $list[$key]['pic_url'] =$pic_url;
		}
	    $row['list'] = $list;
	    return $row;
	}

	function getHouseInfo($id){
		$id = intval($_GET['id']);
		$house = M('house')->where("id={$id}")->find();
		$image=M('images')->field('thumb_url')->where("tid={$id}")->select();
		foreach($image as $key=>$value){
			$image[$key]['index'] = $key;
		}
		$house['image'] =$image;
		$house['birth_time']= date('Y-m-d',$house['birth_time']);
        $house['unit_price'] = $house['price']/$house['size']*10000;
        $house['xian_name']=M('Area')->getFieldById($house['xian'],'name');
        $house['zhen_name']=M('Area')->getFieldById($house['zhen'],'name');
        $house['village_name']=M('Area')->getFieldById($house['village'],'name');
        $house['unit_price'] = round($house['price']/$house['size']*10000);
		return $house;
	}

?>