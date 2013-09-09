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
<tr><td>git组名:  <?php echo $info['group_name']?></td></tr>
<tr><td>git组说明:  <?php echo $info['group_description']?></td></tr>
<tr><td>创建时间：  <?php echo  date("Y-m-d H:m:s",$info['addtime']);?></td></tr>
<tr><td>相关账号：<?php foreach($info['git_accounts'] as $git){ echo " <span>{$git['git_account']}、</span>";}?></td></tr>
<tr><td>申请者:<?php echo $change['realname']?></td></tr>
<?php echo form_open("groupcreator/save/$info[gcre_id]")?>
<tr><td><label class='radio inline'>审批：</label>    <label class='radio inline'><input type="radio" name="gcre_state" value='1' checked>通过 </label><label class='radio inline'> <input type="radio" name="gcre_state" value='-1'>驳回</label></td></tr>
 <tr><td><label>审核备注：</label><textarea name="gcre_description" class="span6" rows="5"></textarea></td></tr>
 <tr><td> <input type="submit" class="btn btn-primary" value="提交"/></td></tr>
 <?php echo form_close()?>
</table>
</div>
</body>
</html>