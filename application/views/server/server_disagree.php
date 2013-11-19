<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
    <title><?php echo $title ?></title>
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
				<h4><?php echo $title ?>
				</h4>
			</div>
			<form action="<?php echo site_url('/server_approve/server_save_disagree')?>" method="post"id="form_data">
			<label for="description">退回原因：</label>
			<textarea rows="4" class="span5" name="description" id="description"></textarea>
                        <input type="hidden" name="sa_id" value="<?php echo $sa_id ?>">
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
			parent.layer.alert('驳回原因不能为空！',9,'123');
			return false;
		}
        var data = $("#form_data").serialize();
	$.post(href,data,function(json_data){
		if(json_data.status==1)
		{
			parent.layer.alert(json_data.msg,9,'消息提示',function(){
                            parent.location.reload();
                        });
                        $('#sendmsg').val(1);
		}
		else
		{
			$('#sendmsg').val(0);
			parent.layer.alert(json_data.msg,8,'错误提示！');
		}
			
		},'json');
	return false;
});
});
</script>
</body>
</html>