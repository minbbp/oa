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
    <script src="<?=base_url()?>/bootstrap/layer/layer.min.js"></script>
    <!-- bootstrap end -->
  </head>
  <body>
 <div class="span6">
	<div class="page-header">
		<h4>加入新的git组</h4>
	</div>
	<form action="<?php echo base_url('index.php/git/savegroup')?>" method="post"  class="form-horizontal">
	<input type="hidden" name="git_id" value="<?php echo $git_id;?>"/>
	<div class="control-group ">
	<label for="group" class="control-label">git组:&nbsp;</label>
	<?php foreach($nogroups as $group):?>
	<label class="inline checkbox">
	<input type="checkbox" name="group_id[]" value='<?php echo $group['group_id'];?>' /><?php echo $group['group_name']?>
	</label>
	<?php endforeach;?>
	</div>
	<label></label>
	<div class="control-group ">
	<label class="control-label">
	
	</label>
	<a href="javascript::" id="reset" class="btn">取消</a>&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="submit" value="提交" class="btn btn-primary" id="submit"/> 
	</div>
	</form>
	</div>
	<script type="text/javascript">
	var index = parent.layer.getFrameIndex(window.name);
	$('#reset').click(function()
		{
			parent.layer.close(index);
		});
	$("#submit").click(function(){
		//保存用户添加的key 的信息
		
			var href=$("form").attr('action');
			$.post(href,$("form").serialize(),function(data){
				
					if(data==1)
					{
						parent.layer.alert('我们已经通知相人员进行审批了 ',9,'提示消息',myclose);
						
					}
					else
					{
						parent.layer.alert(data,8,'错误提示');
					}
				});
			return false;
		
		});
	function myclose(newindex)
	{
		parent.layer.close(newindex);
		parent.layer.close(index);
	}
	</script>
	</body>
</html>