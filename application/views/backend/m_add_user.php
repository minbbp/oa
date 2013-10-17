<?php
$username = array(
	'name'	=> 'username',
	'id'	=> 'username',
	'size'	=> 30,
	'value' =>  set_value('username',$userinfo['username'])
);
$realname = array(
	'name'	=> 'realname',
	'id'	=> 'realname',
	'size'	=> 30,
	'value' =>  set_value('realname',$userinfo['realname'])
);


$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'maxlength'	=> 80,
	'size'	=> 30,
	'value'	=> set_value('email',$userinfo['email'])
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
<fieldset>
<legend>添加用户 </legend>
<?php echo form_open('backend/m_saveuser/'.$userinfo['id'])?>

<dl class="dl-horizontal">
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
	<option value="<?php echo $role['id'];?>" <?php if($role['id']==$userinfo['role_id']){echo 'selected';}?>><?php echo $role['name']?></option>
	<?php endforeach;?>
	</select>
	</dd>
	<dt><?php echo form_label('上级主管：','pid')?></dt>
	<dd>
	<select name="pid">
	<option value="0">无</option>
	<?php foreach ($users as $user):?>
	<option value="<?php echo $user->id;?>" <?php if($user->id=$userinfo['pid']){echo 'selected';}?>><?php echo $user->realname;?></option>
	<?php endforeach;?>
	</select>
	</dd>	
	
	<dt></dt>
	<dd><a href="javascript:history.back(-1)"  class="btn ">不理返回</a>&nbsp;&nbsp;<?php echo form_submit('register','提交修改',"class='btn btn-primary'");?></dd>
</dl>

<?php echo form_close()?>
</fieldset>
</div>
</body>
</html>