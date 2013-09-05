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

$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
	'value' => set_value('password')
);

$confirm_password = array(
	'name'	=> 'confirm_password',
	'id'	=> 'confirm_password',
	'size'	=> 30,
	'value' => set_value('confirm_password')
);

$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'maxlength'	=> 80,
	'size'	=> 30,
	'value'	=> set_value('email')
);

$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha'
);
?>

<html>
<body>
<fieldset><legend>添加用户</legend>
<?php echo form_open($this->uri->uri_string())?>

<dl>
	<dt><?php echo form_label('用户名：', $username['id']);?></dt>
	<dd>
		<?php echo form_input($username)?>
    <?php echo form_error($username['name']); ?>
	</dd>
	 <dt><?php echo form_label('真实姓名:',$realname['id'])?></dt>
	 <dd>
	 <?php echo form_input($realname)?>
	 <?php echo form_error($realname['name'])?>
	 </dd>
	<dt><?php echo form_label('密码：', $password['id']);?></dt>
	<dd>
		<?php echo form_password($password)?>
    <?php echo form_error($password['name']); ?>
	</dd>

	<dt><?php echo form_label('确认密码：', $confirm_password['id']);?></dt>
	<dd>
		<?php echo form_password($confirm_password);?>
		<?php echo form_error($confirm_password['name']); ?>
	</dd>

	<dt><?php echo form_label('邮件：', $email['id']);?></dt>
	<dd>
		<?php echo form_input($email);?>
		<?php echo form_error($email['name']); ?>
	</dd>
	<dt><?php echo form_label('是否为主管：')?></dt>
	<dd>
	<select name="level" style="width:120px">
	<option value="0" >否</option>
	<option value="1">是</option>
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
	<dd><?php echo form_submit('register','添加用户');?></dd>
</dl>

<?php echo form_close()?>
</fieldset>
</body>
</html>