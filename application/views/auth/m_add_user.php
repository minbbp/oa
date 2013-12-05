<?php
$username = array(
	'name'	=> 'username',
	'id'	=> 'username',
	'size'	=> 30,
	'value' =>  set_value('username')
);
$realname = array(
	'name'	=> 'realname',
	'id'	=> 'realname',
	'size'	=> 30,
	'value' =>  set_value('realname')
);


$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'maxlength'	=> 80,
	'size'	=> 30,
	'value'	=> set_value('email')
);


?>

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
 <div class="span8 offset1">
<div class="page-header">
	<h3>添加用户 </h3>
</div>

<?php echo form_open($this->uri->uri_string())?>

<dl class="dl-horizontal">
<dt>用户默认密码：</dt>
<dd>optest</dd>
	<dt><?php echo form_label('用户名：', $username['id']);?></dt>
	<dd>
		<?php echo form_input($username)?>
    <?php echo form_error($username['name']); ?>
	</dd>
	 <dt><?php echo form_label('真实姓名：',$realname['id'])?></dt>
	 <dd>
	 <?php echo form_input($realname)?>
	 <?php echo form_error($realname['name'])?>
	 </dd>
	

	

	<dt><?php echo form_label('邮件：', $email['id']);?></dt>
	<dd>
		<?php echo form_input($email);?>
		<?php echo form_error($email['name']); ?>
	</dd>
	
	<dt><label for="role_id">用户角色：</label></dt>
	<dd>
	<select name="role_id">
	<?php foreach($roles as $role):?>
	<option value="<?php echo $role['id'];?>"><?php echo $role['name']?></option>
	<?php endforeach;?>
	</select>
	</dd>
	<dt><?php echo form_label('上级主管：','pid')?></dt>
	<dd>
	<select name="pid">
	<option value="0">无</option>
	<?php foreach ($users as $user):?>
	<option value="<?php echo $user->id;?>"><?php echo $user->realname;?></option>
	<?php endforeach;?>
	</select>
	</dd>	
	<dt></dt>
	<dd class="text-center"><?php echo form_submit('register','添加用户',"class='btn btn-primary'");?></dd>
</dl>

<?php echo form_close()?>
</fieldset>
</div>
</body>
</html>