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
<style>
#show{display:none;}
</style>
  </head>
  <body>
 <div class="span8">
			<div class="page-header">
				<h3>
					审批<?php echo $userinfo['username']?>的git 账号
				</h3>
			</div>
			<?php echo form_open('git/h_apply_change')?>
			<table class="table   table-bordered">
			<tr><td>申请人：<?php echo  $userinfo['realname'] ?></td></tr>
			<tr><td>git账号所属组：<?php foreach($gits['add_datagroups'] as $groups){echo "<span><u>{$groups[group_name]}</u>&nbsp;&nbsp;&nbsp;</span>";} ?></td></tr>
			<tr><td>申请时间：<?php echo  date("Y-m-d H:i:s",$gits['addtime'])?></td></tr>
			<tr><td> 处理：<input type="radio" id="showrrs" name="h_state"  checked value="1"/>&nbsp;&nbsp;通过&nbsp;<input type="radio" name="h_state" value="-1" id="showrs" />&nbsp;&nbsp;不通过&nbsp;</td></tr>
			<tr id="show"><td>失败原因：<textarea name="h_description" class="span5" rows="6"></textarea></td></tr>
			<tr><td><input type="hidden" name="git_id" value=<?php echo $gits['git_id'];?> /><input type="submit" class="btn btn-primary" value="提交"/></td></tr>
			</table>
			<?php echo form_close()?>
			</div>
			<script>
			$(function(){
				$("#showrs").click(function()
					{
						$("#show").show();
					});
				$("#showrrs").click(function()
					{
						$("#show").hide();
					});
				});
			</script>
			</body>
			</html>