<?php if (!defined('THINK_PATH')) exit();?><html>
<body>
<link href="__ROOT__/Style/bootstrap-3.3.5/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<script src="http://www.baicai58.com/Style/Js/jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href="/Editor/themes/default/default.css" />
<script charset="utf-8" src="/Editor/kindeditor-min.js"></script>
<script charset="utf-8" src="/Editor/lang/zh_CN.js"></script>
<style>

.drop_area{
	height:170px;background-color:silver;margin:5px 0px;overflow:auto;position:relative;
	
}
.drop_area span{
	position:absolute;font-size:20px;color:#fff;
	top:45%;left:35%;text-align:center;display:block;

}
.form-group{
	
	padding-bottom: 2px;
}
.form-control{
	border:0;
	box-shadow:none;
	border-radius:0;
	border-bottom: 1px solid silver;
}
</style>
<script>
  var editor;//初始化Editor
    KindEditor.ready(function(K) {
    editor = K.create('textarea[name="detail"]', {
	height : "500px",
	width:"700px",
    resizeType : 0,//定义编辑器是否可自由控制
    allowFileManager : true,
	afterBlur: function(){this.sync();}//当失去焦点时执行 this.sync();
                });
                
    K('#resetname').click(function(e) {
        editor.html('');//重置数据
        });
    });
</script>
<script>	
	var minfo = new Array();
	var linfo = new Array();
	var binfo = new Array();
$(function(){ 
	$("#sumbit").click(function(){	
		var name = $('#name').val();		
		var status = true;		
		$.ajax({
			url: "__URL__/verifyname",
			type: "post",
			dataType: "json",
			data: {"name":name},
			async:false,
			success: function(result) {
				if (result.status == 0){
						status=false;	
				}else if(result.status==1){
					alert(result.message);
				}
			},
			error:function(){
				alert('出错了');} 
		})
		if(status){
		 return false;
		}
		var mortgage = $('#mortgage').val();
		var loan_amount = $('#loan_amount').val();
		var loan_duration = $('#loan_duration').val();
		var collect_day = $('#collect_day').val();
		var interest_rate = $('#interest_rate').val();
		var fee_rate = $('#fee_rate').val();
		var min_invest = $('#min_invest').val();
		var detail = $('#editor_1').val();
		
		if (!name||!mortgage||!interest_rate||!fee_rate||!detail){
			alert("信息不完整！");
			return  false;
		}
		
		if (loan_amount/1<=0||loan_amount%1!= 0) {
			alert("借款金额必须为正整数");
			return false;
		}
		
		if (loan_duration/1<=0||loan_duration%1!= 0) {
			alert("借款期限必须为整数");
			return false;
		} 
		if (collect_day/1<=0||collect_day%1!= 0) {
			alert("募集期限必须为整数天");
			return false;
		} 
		
		if (min_invest/1<=0||min_invest%1!= 0){
			alert("最小投资额必须为正整数");
			return  false;
		} 

	    $('#sumbit').attr("disabled",true).val("提交中...");
	
		
	    var formData = new FormData($('#Borrow')[0]);
		var detail = $('#editor_1').val();
		for(var i = 0;i<minfo.length;i++){
			formData.append("minfo[]",minfo[i]);  
		}
		for(var i = 0;i<linfo.length;i++){
			formData.append("linfo[]",linfo[i]);   
		}
		 for(var i = 0;i<binfo.length;i++)
			formData.append("binfo[]",binfo[i]);  		
		$.ajax(
			{
			
				url: "__URL__/dopublish/",
				type: "post",
				dataType: "json",
				data:formData,
				async: false,  			
         		cache: false,  
				contentType: false,  
				processData: false,  
				success:function(result){				　	
					$('#sumbit').val("提交");					
					if(result.status == 1){					  
						alert(result.message);
						$('#sumbit').attr("disabled",false);
						 
					}else if(result.status==2){
						$('input[type="button"]').css("background-color","gray");
						alert(result.message);
						window.location.href = window.location.href;
					}				
				},
				error:function(){
					alert('error');
				}		
			}	
		);
	});
	
    //阻止浏览器默认行。 
    $(document).on({ 	  
        dragleave:function(e){    //拖离 
            e.preventDefault(); 
        }, 
        drop:function(e){  //拖后放 
            e.preventDefault(); 
        }, 
        dragenter:function(e){    //拖进 
            e.preventDefault(); 
        }, 
        dragover:function(e){    //拖来拖去 
            e.preventDefault(); 
        } 
    }); 
	var box = document.getElementsByClassName("drop_area");
	var size1 = -1;
	var size2 = -1;
	var size3 = -1;
    $(box).each(function(index){
		box[index].addEventListener("drop",function(e){ 
			e.preventDefault(); //取消默认浏览器拖拽效果 
			var fileList = e.dataTransfer.files; //获取文件对象 
			//alert(fileList[0]);
			for(var i = 0;i <　fileList.length;i++){
				//检测是否是拖拽文件到页面的操作 
				if(fileList.length == 0){ 
					return false; 
				} 
				//检测文件是不是图片 
				if(fileList[i].type.indexOf('image') === -1){ 
					alert("您拖的不是图片！"); 
					return false; 
				} 
				if($.browser.mozilla)			
					img= window.URL.createObjectURL(fileList[i]);
				else
					img = window.webkitURL.createObjectURL(fileList[i]); 
				
				var filename = fileList[i].name; //图片名称 
				var filesize = Math.floor((fileList[i].size)/1024);  
				if(index == 0){
					minfo.push(fileList[i]);
					size1++;
					var str = add(size1,index,img);	
					$(this).append(str); 
				}else if(index == 1){
					linfo.push(fileList[i]);
					size2++;
					var str = add(size2,index,img);
					$(this).append(str); 
				}else if(index == 2){
					binfo.push(fileList[i]);
					size3++;
					var str = add(size3,index,img);
					$(this).append(str); 
				//formData.append("c[]",fileList[i]);		
				}
			}
		},false); 
	});
}); 


function a(index,size){
	switch(index){
	case 0:
		minfo.splice(size,1);
		break;
	case 1:
		linfo.splice(size,1);
		break;
	case 2:
		binfo.splice(size,1);
		break;
	}		
	$(".drop_area:eq("+index+") #img"+size).remove();		
}

function add(size,index,img){
	var str = "<div style='margin-right:10px;float:left;' id='img"+size+"'><img src='/Style/A/images/delete.jpg' style='width:10px;height:10px;' onclick='a("+index+","+size+");'><img src='"+img+"' style='width:80px;height:80px'></div>"; 
	return str;
	

}

</script>






<div class="col-xs-12">

	
		<h4>基本信息</h4>
		<hr>	
	<form class="form-horizontal" role="form" id="Borrow"  enctype="multipart/form-data">
		<div class="form-group">
	      <label for="firstname" class="col-xs-2 control-label">项目名称:</label>
	      <div class="col-xs-6">
			  <input type="text" class="form-control" value="财日升" readonly 
	           > 
          </div>
        </div>
		
		<div class="form-group">
	      <label for="firstname" class="col-xs-2 control-label">还款方式选择:</label>
	      <div class="col-xs-6">
	      		<input type="text" class="form-control" value="到期还本付息" readonly 
	           > 
          </div>
        </div>


        <div class="form-group">
	      <label for="name" class="col-xs-2 control-label">借款人:</label>
	      <div class="col-xs-6">
	         <input type="text" class="form-control" id ="name" name="name"
	           >
	      </div>
	      <div class="col-xs-2"><span class="error_box" style="color:red;"> </span></div>
  		</div>

  		<div class="form-group">
	      <label for="mortgage" class="col-xs-2 control-label">质押物估价:</label>
	      <div class="col-xs-6">
	         <input type="number" class="form-control" id ="mortgage" name="mortgage"
	            min="1" max="10000000">
	      </div>
  		</div>


  		<div class="form-group">
	      <label for="loan_amount" class="col-xs-2 control-label">借款金额:</label>
	      <div class="col-xs-6">
	         <input type="number" class="form-control" id ="loan_amount" name="loan_amount"
	             min="1" max="5000000">
	      </div>
  		</div>

  		<div class="form-group">
	      <label for="loan_duration" class="col-xs-2 control-label">借款期限（天）:</label>
	      <div class="col-xs-6">
	         <input type="number" class="form-control" id ="loan_duration" name="loan_duration"
	             min="1" max="100">
	      </div>
  		</div>

	     <div class="form-group">
	      <label for="collect_day" class="col-xs-2 control-label">募集期限（天）:</label>
	      <div class="col-xs-6">
	         <input type="number" class="form-control" id ="collect_day" name="collect_day"
	             min="1" max="7">
	      </div>
  		</div>


	     <div class="form-group">
	      <label for="interest_rate" class="col-xs-2 control-label">借款利率:</label>
	      <div class="col-xs-6">
	         <input type="number" class="form-control" id ="interest_rate" name="interest_rate"
	             min="1" max="20">
	      </div>
  		</div>

  		 <div class="form-group">
	      <label for="fee_rate" class="col-xs-2 control-label">平台费率:</label>
	      <div class="col-xs-6">
	         <input type="number" class="form-control" id ="fee_rate" name="fee_rate"
	            min="1" max="20">
	      </div>
  		</div>

  		<div class="form-group">
	      <label for="min_invest" class="col-xs-2 control-label">最低投资额:</label>
	      <div class="col-xs-6">
	         <input type="number" class="form-control" id ="min_invest" name="min_invest"
	            min="1" max="100">
	      </div>
  		</div>

	  
  

  	<h4>项目介绍</h4>
		<hr>	
		<div class="col-xs-offset-1 col-xs-8">
		<div style="height:200px;">
						<div class="post_ueditor">
							<textarea class="editor" id="editor_1" name="detail"></textarea>
                        </div>	
                        <div style="margin-left:300px;">
						<a class="btn btn-success" id="resetname" title="重置内容">重置内容</a>       
                         </div>
                        
		</div>
		


		<div style="margin-top:650px;" >
			<h4>图片资料</h4>
			<hr>		
			<div class="drop_area" >
				<span>拖动借款人资料图片到这里</span>
			</div>
			<div class="drop_area" >
				<span>拖动债权人资料图片到这里</span>
			</div>
			<div class="drop_area">
				<span>拖动担保人资料图片到这里</span>
			</div>
		</div>	
		</div>



<hr>
<div class="col-xs-offset-1 col-xs-8 text-center">
   <button class="btn btn-success " value="提交" id="sumbit">提交</button>
</div>
</form>
</div>


<script>
$(function(){
	$('.container div').each(function(index){
			$(this).click(function(){
			//alert(index);
			$('.container div:eq('+index+')').css({'color':'blue','background-color':'#fff'}).siblings().css({'color':'black','background-color':'inherit'});
			$('.main .box1:eq('+index+')').show().siblings().hide();
			});
			
		});
});



function shutdown(){
		$("#div1").hide();
	}
	function setError(tip){
		$.jBox.tip(tip);
		return false;
	}

</script>
</body>
</html>