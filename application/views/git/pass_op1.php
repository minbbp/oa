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
 <div class="span6" style="margin-left:10px;">
			<div class="page-header">
				<h4>
					git认证审批 _分配受控目录和机器标识
				</h4>
			</div>
			<form action="<?php echo base_url('index.php/git_ops/savepass1')?>" id="myform" method="post">
			<input type="hidden" name="gits_opid" value="<?php echo $gits_oprs['gits_opid'];?>"> 
			<input type="hidden" name="git_id" value="<?php echo $gits_oprs['git_id'];?>"> 
			<label for="cfilename">受控目录：</label>
			<input type="text" name="cfilename" id="cfilename"/>
			<label>机器标识：</label>
			<?php 
			$i=0;
			
			foreach($keys as $k)
			{
				++$i;
				if($k['key_state']==0)
				{
					
					echo "<input type='text' name='git_auth[]'><small><=对应=>{$k['gitpub']}</small> ";
					echo "<input type='hidden' name='key_id[]' value='".$k['key_id']."'/>";
				}
			}
			?>
			<label >
			
			<a href="javascript::void(0)" class="btn btn-danger" id="reset" >取消</a>&nbsp;&nbsp;&nbsp;
			<input type="submit" id="submit" class="btn btn-success " value="保存"/>
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
	if($('#cfilename').val()=="")
	{
			parent.layer.alert('受控目录不能为空！',9,'');
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