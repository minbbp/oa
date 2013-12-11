<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
    <title><?php echo $title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="<?=base_url()?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<script src="<?=base_url()?>/bootstrap/js/jquery-1.10.2.min.js"></script>
    <script src="<?=base_url()?>/bootstrap/js/bootstrap.min.js"></script>
    <!-- bootstrap end -->
  </head>
  <body>
 <div class="span6"  style="margin-left:10px">
			<div class="page-header">
				<h4>
					<?php echo $title?>
				</h4>
			</div>
			<form action="<?php echo base_url('index.php/grouplevel/bohui/'.$gle_id,'id="bohui"')?>" method="post">
			<label for='gle_description'>驳回原因:</label>
			<textarea rows="3" class="span6" name="gle_description" id='gle_description'></textarea>
			<label></label>
			<a href="javascript::void(0)" class="btn btn-danger xubox_close xulayer_png32 xubox_close0" id="reset" >取消</a>&nbsp;&nbsp;&nbsp;
			<input type="submit" id="submit" class="btn btn-primary " value="保存"/>
			</form>
</div>
<input type="hidden" name="sendmsg" id="sendmsg" value='0' />
<script type="text/javascript">
var index = parent.layer.getFrameIndex(window.name);
$(function(){
$("#reset").click(function(){
	//console.log($('.xubox_close').html());
 //$('#sendmsg').val();
 parent.layer.close(index);
});

$("#submit").click(function(){
	var href=$('form').attr('action');
	var time=new Date().getTime();
	if($('#gle_description').val()=="")
		{
			parent.layer.alert('驳回原因不能为空！',9,'');
			return false;
		}
	var description=$('#gle_description').val();
	$.post(href,{msg:description,time:time},function(data){
		data=data.split('_');
		if(data[1]==1)
		{
			parent.layer.alert('驳回申请成功！',9,'消息提示');
			$('#sendmsg').val(1);
			//parent.layer.close(index);
		}
		else
		{
			$('#sendmsg').val(0);
			parent.layer.alert(data[1],8,'错误提示！');
		}
			
		});
	return false;
});
});
</script>
</body>
</html>