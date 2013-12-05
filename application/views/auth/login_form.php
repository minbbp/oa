<?php
$username = array(
	'name'	=> 'username',
	'id'	=> 'username',
	'size'	=> 30,
	'value' => set_value('username'),
	'placeholder'=>'用户名'
);

$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
    'placeholder'=>'密码'
);

$remember = array(
	'name'	=> 'remember',
	'id'	=> 'remember',
	'value'	=> 1,
	'checked'	=> set_value('remember'),
);

$confirmation_code = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8
);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>运维OA_login</title>
<link href="<?php echo base_url("bootstrap/css/bootstrap.min.css")?>" rel="stylesheet" media="screen">
<link href="<?php echo base_url("bootstrap/css/login.css");?>" rel="stylesheet" media="screen">
</head>

<body>

<body class="loginpage">

	<div class="loginbox">
    	<div class="loginboxinner">
        	
            <div class="logo">
            	<h1><span>运维OA登陆</span></h1>
            </div><!--logo-->
            
            <br clear="all"><br>
            
            
            
            <?php echo form_open($this->uri->uri_string(),array('id'=>'login'));?>
			<?php echo $this->dx_auth->get_auth_error(); ?>
            	
                <div class="username">
                	<div class="usernameinner">
                	<?php echo form_input($username)?>
   					 <?php echo form_error($username['name']); ?>
                    </div>
                </div>
                
                <div class="password">
                	<div class="passwordinner">
                    	<?php echo form_password($password)?>
    					<?php echo form_error($password['name']); ?>
                    </div>
                </div>
                <?php if ($show_captcha): ?>
                <dl>
				<dt>请输入验证码</dt>
				<dd><?php echo $this->dx_auth->get_captcha_image(); ?></dd>
				<dt><?php echo form_label('验证码：', $confirmation_code['id']);?></dt>
				<dd>
					<?php echo form_input($confirmation_code);?>
					<?php echo form_error($confirmation_code['name']); ?>
				</dd>
				</dl>
				<?php endif; ?>
                <div class="keep"><?php echo form_checkbox($remember);?><span>记住我</span></div>
                <button type="submit">登 陆</button>
            <?php echo form_close()?>
        </div><!--loginboxinner-->
    </div><!--loginbox-->
   <script type="text/javascript">
    <!--
    if (parent.frames.length > 0) { parent.location.href=location.href;}
    -->
    </script>
</body>
</body>
</html>
