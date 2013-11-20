<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
    <title>驳回上线申请</title>
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
					代码上线负责人审批
				</h4>
			</div>
			<form action="<?php echo base_url('index.php/codeonline_op/savereject/'.$a_id)?>" method="post">
			<label for="description">驳回原因：</label>
			<textarea rows="4" class="span5" name="description" id="description"></textarea>
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
	if($('#description').val()=="")
		{
			parent.layer.alert('驳回原因不能为空！',9,'');
			return false;
		}
	var description=$('#description').val();
	$.post(href,{description:description,time:time},function(data){
		if(data.status==1)
		{
			parent.layer.alert(data.msg,9,'消息提示');
			$('#sendmsg').val(1);
			//parent.layer.close(index);
		}
		else
		{
			$('#sendmsg').val(0);
			parent.layer.alert(data.msg,8,'错误提示！');
		}
			
		},'json');
	return false;
});
});
</script>
</body>
</html>