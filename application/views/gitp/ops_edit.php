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
 <div class="span8">
 <div class="page-header">
				<h3>
					<?php echo $title?>
				</h3>
</div>
<table class="table table-bordered">
<tr><td>git组名:  <?php echo $group_rs['group_name']?></td></tr>
<tr><td>git组说明:  <?php echo $group_rs['group_description']?></td></tr>
<tr><td>创建时间：  <?php echo  date("Y-m-d H:m:s",$group_rs['addtime']);?></td></tr>
<tr><td class="text-error">操作说明：请把<?php echo $group_rs['group_name']?>组的用户更新为:<?php foreach($gitaccount as $git){ echo " <span>{$git['git_account']}、</span>";}?></td></tr>
<tr><td>申请者:<?php echo $userinfo['realname']?></td></tr>
<?php echo form_open("groupops/save/$grop_rs[gop_id]")?>
<tr><td class="text-error"><label class='radio inline'> 操作结果：</label>    <label class='radio inline'><input type="radio" name="gop_state" value='1' checked>通过 </label><label class='radio inline'> <input type="radio" name="gop_state" value='-1'>未通过</label></td></tr>
<tr><td class="text-error"><label class='radio inline'>    组状态：</label>    <label class='radio inline'><input type="radio" name="group_state" value='1' checked>可用 </label><label class='radio inline'> <input type="radio" name="group_state" value='-1'>不可用</label></td></tr>
<tr><td class="text-error"><label class='radio inline'> 发送邮件：</label>    <label class='radio inline'><input type="radio" name="sendmsg" value='1' checked>发送</label><label class='radio inline'> <input type="radio" name="sendmsg" value='-1'>不 发送</label><?php if($userinfo['level']==0):?>&nbsp;&nbsp;&nbsp;<label class="checkbox inline"><input type="checkbox" name="sendlevel" value=1> 告知主管</label><?php endif;?></td></tr>
 <tr><td><label>操作备注：</label><textarea name="gop_description" class="span6" rows="5"></textarea></td></tr>
 
 <tr><td> <input type="hidden" name="group_id" value="<?php echo $group_rs['group_id'];?>"><p class="text-right"><input type="submit" class="btn btn-primary " value="提交"/></p></td></tr>
 <?php echo form_close()?>
</table>
</div>
</body>
</html>