<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
    <title>运维OA_login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="<?=base_url()?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<script src="<?=base_url()?>/bootstrap/js/jquery-1.10.2.min.js"></script>
    <script src="<?=base_url()?>/bootstrap/js/bootstrap.min.js"></script>
    <!-- bootstrap end -->
  </head>
  <body>
 <div class="span6">
			<div class="page-header">
				<h4>
					git认证审批 _机器标识添加
				</h4>
			</div>
			<form action="<?php echo base_url('index.php/git_ops/savepass2')?>" id="myform" method="post">
			<input type="hidden" name="gits_opid" value="<?php echo $gits_opid;?>"> 
			<input type="hidden" name="git_id" value="<?php echo $git_id;?>"> 
			
			<label>机器标识：</label>
			<input type='text' name='git_auth' id='git_auth'><small><=对应=><?php echo $filename;?></small> 
			<input type='hidden' name='gitpub' value="<?php echo $filename;?>"/>
			<label></label>
			<label></label>
			<label >
			
			<a href="javascript::void(0)" class="btn btn-danger" id="reset" >取消</a>&nbsp;&nbsp;&nbsp;
			<input type="submit" id="submit" class="btn btn-primary " value="保存"/>
			 </label>
			</form>
</div>
<input type="hidden" name="sendmsg" id="sendmsg" value='0' />
<script type="text/javascript">
var index = parent.layer.getFrameIndex(window.name);
$(function(){
$("#reset").click(function(){
  parent.layer.close(index);
});

$("#submit").click(function(){
	var href=$('form').attr('action');
	var time=new Date().getTime();
	if($('#git_auth').val()=="")
	{
			parent.layer.alert('机器标识不能为空！',9,'');
			return false;
	}
	$.post(href+"/"+time,$("#myform").serialize(),function(data){
		
			parent.layer.alert(data,9,'消息提示');
			$('#sendmsg').val(1);
			//parent.layer.close(index);
			
		});
	return false;
	
});
});
</script>
</body>
</html>